<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Armada;
use App\Services\SeatAvailabilityService;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        // 1. Validate capacity
        $armada = Armada::find($data['armada_id']);
        if ($data['total_passengers'] > $armada->capacity) {
            Notification::make()
                ->danger()
                ->title('Kapasitas Melebihi Batas')
                ->body("Armada {$armada->name} hanya memiliki {$armada->capacity} kursi")
                ->persistent()
                ->send();
            $this->halt();
        }

        // 2. Validate seat selection if armada has seat layout
        if ($armada->seatLayout) {
            $selectedSeats = json_decode($data['selected_seats'] ?? '[]', true);

            // Check if seats are selected
            if (empty($selectedSeats)) {
                Notification::make()
                    ->danger()
                    ->title('Kursi Belum Dipilih')
                    ->body('Silakan pilih kursi terlebih dahulu')
                    ->persistent()
                    ->send();
                $this->halt();
            }

            // Check if seat count matches passengers
            if (count($selectedSeats) !== (int)$data['total_passengers']) {
                Notification::make()
                    ->danger()
                    ->title('Jumlah Kursi Tidak Sesuai')
                    ->body("Pilih {$data['total_passengers']} kursi sesuai jumlah penumpang")
                    ->persistent()
                    ->send();
                $this->halt();
            }

            // 3. Validate seat availability (prevent double-booking)
            try {
                app(SeatAvailabilityService::class)->validateSeatAvailability(
                    $data['armada_id'],
                    $data['travel_date'],
                    $data['travel_time'] ?? null,
                    $selectedSeats
                );
            } catch (\Exception $e) {
                Notification::make()
                    ->danger()
                    ->title('Kursi Tidak Tersedia')
                    ->body($e->getMessage())
                    ->persistent()
                    ->send();
                $this->halt();
            }
        }
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $armada = Armada::find($data['armada_id']);

        // Reserve seats if armada has seat layout
        if ($armada->seatLayout) {
            $selectedSeats = json_decode($data['selected_seats'] ?? '[]', true);

            if (!empty($selectedSeats)) {
                try {
                    app(SeatAvailabilityService::class)->reserveSeats(
                        $this->record->id,
                        $selectedSeats
                    );

                    Notification::make()
                        ->success()
                        ->title('Booking Berhasil')
                        ->body('Kursi ' . implode(', ', $selectedSeats) . ' telah dipesan')
                        ->send();
                } catch (\Exception $e) {
                    // Rollback - delete the booking
                    $this->record->delete();

                    Notification::make()
                        ->danger()
                        ->title('Gagal Memesan Kursi')
                        ->body($e->getMessage())
                        ->persistent()
                        ->send();

                    $this->halt();
                }
            }
        }
    }
}
