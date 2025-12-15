<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // Quick action to mark as completed
            Action::make('mark_completed')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => in_array($this->record->status, ['pending', 'confirmed']))
                ->requiresConfirmation()
                ->modalHeading('Tandai Booking Sudah Selesai?')
                ->modalDescription('Trip sudah sampai tujuan. Kursi akan otomatis tersedia untuk booking berikutnya.')
                ->action(function () {
                    $this->record->update(['status' => 'completed']);

                    // Invalidate cache to release seats
                    \App\Models\SeatAvailabilityCache::where('armada_id', $this->record->armada_id)
                        ->where('travel_date', $this->record->travel_date->format('Y-m-d'))
                        ->delete();

                    Notification::make()
                        ->success()
                        ->title('Trip Selesai')
                        ->body('Kursi sudah dilepas dan tersedia untuk booking lain.')
                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),

            // Quick action to confirm booking
            Action::make('confirm')
                ->label('Konfirmasi Booking')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->visible(fn() => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Booking Ini?')
                ->modalDescription('Pembayaran sudah diterima dan booking dikonfirmasi.')
                ->action(function () {
                    $this->record->update(['status' => 'confirmed']);

                    Notification::make()
                        ->success()
                        ->title('Booking Dikonfirmasi')
                        ->body('Status booking berhasil diubah menjadi Confirmed.')
                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),
        ];
    }
}
