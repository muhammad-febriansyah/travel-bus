<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SeatAssignment extends Model
{
    protected $fillable = [
        'booking_id',
        'seat_number',
        'passenger_name',
        'status',
    ];

    /**
     * Get the booking that owns this seat assignment
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope to filter confirmed seat assignments
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to filter seat assignments for specific armada, date and time
     * Only returns seats for upcoming/active trips (auto-release past trips)
     */
    public function scopeForArmadaAndDate(Builder $query, int $armadaId, string $date, ?string $time = null): Builder
    {
        return $query->whereHas('booking', function ($q) use ($armadaId, $date, $time) {
            $q->where('armada_id', $armadaId)
                ->where('travel_date', $date)
                ->whereIn('status', ['pending', 'confirmed']); // Exclude 'completed'

            if ($time !== null) {
                $q->where('travel_time', $time);
            }

            // Auto-release: Only consider bookings that haven't departed yet
            $q->where(function($subQuery) use ($date, $time) {
                $now = now();
                $bookingDateTime = $date . ' ' . ($time ?? '00:00:00');

                // If booking date+time is in the future, seat is occupied
                // If booking date+time has passed, seat is released (available)
                $subQuery->whereRaw("CONCAT(travel_date, ' ', COALESCE(travel_time, '00:00:00')) >= ?", [$now->format('Y-m-d H:i:s')]);
            });
        });
    }

    /**
     * Get array of occupied seat numbers for specific armada, date and time
     */
    public static function getOccupiedSeats(int $armadaId, string $date, ?string $time = null): array
    {
        return static::forArmadaAndDate($armadaId, $date, $time)
            ->pluck('seat_number')
            ->toArray();
    }
}
