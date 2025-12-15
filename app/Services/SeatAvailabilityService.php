<?php

namespace App\Services;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\SeatAssignment;
use App\Models\SeatAvailabilityCache;
use Illuminate\Support\Facades\DB;
use Exception;

class SeatAvailabilityService
{
    /**
     * Get occupied seats with caching
     */
    public function getOccupiedSeats(int $armadaId, string $date, ?string $time): array
    {
        return SeatAvailabilityCache::getOccupiedSeats($armadaId, $date, $time);
    }

    /**
     * Validate that requested seats are available
     * Throws exception if any seat is occupied
     */
    public function validateSeatAvailability(int $armadaId, string $date, ?string $time, array $requestedSeats): void
    {
        if (empty($requestedSeats)) {
            throw new Exception('Tidak ada kursi yang dipilih');
        }

        $occupiedSeats = $this->getOccupiedSeats($armadaId, $date, $time);

        $conflicts = array_intersect($requestedSeats, $occupiedSeats);
        if (!empty($conflicts)) {
            $conflictList = implode(', ', $conflicts);
            throw new Exception("Kursi berikut sudah dipesan: {$conflictList}");
        }

        // Validate seats exist in armada's layout
        $armada = Armada::with('seatLayout')->find($armadaId);
        if (!$armada || !$armada->seatLayout) {
            throw new Exception('Layout kursi tidak ditemukan untuk armada ini');
        }

        $validSeats = $armada->seatLayout->seat_numbers;
        $invalidSeats = array_diff($requestedSeats, $validSeats);

        if (!empty($invalidSeats)) {
            $invalidList = implode(', ', $invalidSeats);
            throw new Exception("Kursi tidak valid: {$invalidList}");
        }
    }

    /**
     * Validate seat availability for a DATE (ignore time)
     * Used for customer bookings to prevent double booking on same date
     */
    public function validateSeatAvailabilityForDate(int $armadaId, string $date, array $requestedSeats): void
    {
        if (empty($requestedSeats)) {
            throw new Exception('Tidak ada kursi yang dipilih');
        }

        // Get ALL occupied seats for this date (ignoring time)
        $occupiedSeats = SeatAvailabilityCache::getOccupiedSeatsForDate($armadaId, $date);

        $conflicts = array_intersect($requestedSeats, $occupiedSeats);
        if (!empty($conflicts)) {
            $conflictList = implode(', ', $conflicts);
            throw new Exception("Kursi berikut sudah dipesan untuk tanggal ini: {$conflictList}");
        }

        // Validate seats exist in armada's layout
        $armada = Armada::with('seatLayout')->find($armadaId);
        if (!$armada || !$armada->seatLayout) {
            throw new Exception('Layout kursi tidak ditemukan untuk armada ini');
        }

        $validSeats = $armada->seatLayout->seat_numbers;
        $invalidSeats = array_diff($requestedSeats, $validSeats);

        if (!empty($invalidSeats)) {
            $invalidList = implode(', ', $invalidSeats);
            throw new Exception("Kursi tidak valid: {$invalidList}");
        }
    }

    /**
     * Reserve seats for a booking
     */
    public function reserveSeats(int $bookingId, array $seatNumbers): void
    {
        $booking = Booking::find($bookingId);
        if (!$booking) {
            throw new Exception('Booking tidak ditemukan');
        }

        DB::transaction(function () use ($booking, $seatNumbers) {
            // Validate availability with lock
            $this->validateSeatAvailability(
                $booking->armada_id,
                $booking->travel_date->format('Y-m-d'),
                $booking->travel_time?->format('H:i:s'),
                $seatNumbers
            );

            // Create seat assignments
            foreach ($seatNumbers as $seatNumber) {
                SeatAssignment::create([
                    'booking_id' => $booking->id,
                    'seat_number' => $seatNumber,
                    'status' => $booking->status === 'confirmed' ? 'confirmed' : 'reserved',
                ]);
            }

            // Invalidate cache
            $this->invalidateCache(
                $booking->armada_id,
                $booking->travel_date->format('Y-m-d'),
                $booking->travel_time?->format('H:i:s')
            );
        });
    }

    /**
     * Release all seats for a booking
     */
    public function releaseSeats(int $bookingId): void
    {
        $booking = Booking::with('seatAssignments')->find($bookingId);
        if (!$booking) {
            return;
        }

        DB::transaction(function () use ($booking) {
            // Delete all seat assignments
            $booking->seatAssignments()->delete();

            // Invalidate cache
            $this->invalidateCache(
                $booking->armada_id,
                $booking->travel_date->format('Y-m-d'),
                $booking->travel_time?->format('H:i:s')
            );
        });
    }

    /**
     * Update seat assignments for a booking
     * Releases old seats and reserves new ones
     */
    public function updateSeats(int $bookingId, array $newSeatNumbers): void
    {
        $booking = Booking::find($bookingId);
        if (!$booking) {
            throw new Exception('Booking tidak ditemukan');
        }

        DB::transaction(function () use ($booking, $newSeatNumbers) {
            // Release existing seats
            $booking->seatAssignments()->delete();

            // Reserve new seats
            $this->reserveSeats($booking->id, $newSeatNumbers);
        });
    }

    /**
     * Invalidate seat availability cache
     */
    protected function invalidateCache(int $armadaId, string $date, ?string $time): void
    {
        SeatAvailabilityCache::where('armada_id', $armadaId)
            ->where('travel_date', $date)
            ->where('travel_time', $time)
            ->delete();
    }

    /**
     * Get seat availability summary
     */
    public function getAvailabilitySummary(int $armadaId, string $date, ?string $time): array
    {
        $armada = Armada::with('seatLayout')->find($armadaId);
        if (!$armada) {
            throw new Exception('Armada tidak ditemukan');
        }

        $occupiedSeats = $this->getOccupiedSeats($armadaId, $date, $time);
        $totalCapacity = $armada->capacity;
        $availableCount = $totalCapacity - count($occupiedSeats);

        return [
            'total_capacity' => $totalCapacity,
            'occupied_count' => count($occupiedSeats),
            'available_count' => $availableCount,
            'occupied_seats' => $occupiedSeats,
            'utilization_rate' => $totalCapacity > 0 ? (count($occupiedSeats) / $totalCapacity) * 100 : 0,
        ];
    }
}
