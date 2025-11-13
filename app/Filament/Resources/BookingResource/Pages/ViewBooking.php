<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Booking')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Placeholder::make('booking_code')
                                    ->label('Kode Booking')
                                    ->content(fn ($record) => $record->booking_code),

                                Forms\Components\Placeholder::make('status')
                                    ->label('Status')
                                    ->content(fn ($record) => match ($record->status) {
                                        'pending' => '⏱️ Pending',
                                        'confirmed' => '✓ Confirmed',
                                        'completed' => '✓✓ Completed',
                                        'cancelled' => '✗ Cancelled',
                                        default => ucfirst($record->status),
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->content(fn ($record) => $record->created_at->format('d M Y H:i')),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Diupdate Pada')
                                    ->content(fn ($record) => $record->updated_at->format('d M Y H:i')),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Data Customer')
                    ->schema([
                        Forms\Components\Placeholder::make('customer.name')
                            ->label('Nama Customer')
                            ->content(fn ($record) => $record->customer->name),

                        Forms\Components\Placeholder::make('customer.phone')
                            ->label('No. Telepon')
                            ->content(fn ($record) => $record->customer->phone),

                        Forms\Components\Placeholder::make('customer.email')
                            ->label('Email')
                            ->content(fn ($record) => $record->customer->email ?? '-'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Detail Perjalanan')
                    ->schema([
                        Forms\Components\Placeholder::make('route')
                            ->label('Rute')
                            ->content(fn ($record) => "{$record->route->origin} → {$record->route->destination}")
                            ->columnSpan(2),

                        Forms\Components\Placeholder::make('armada.name')
                            ->label('Armada')
                            ->content(fn ($record) => "{$record->armada->name} ({$record->armada->plate_number})"),

                        Forms\Components\Placeholder::make('category.name')
                            ->label('Kategori')
                            ->content(fn ($record) => $record->category->name),

                        Forms\Components\Placeholder::make('travel_date')
                            ->label('Tanggal Perjalanan')
                            ->content(fn ($record) => $record->travel_date->format('d M Y')),

                        Forms\Components\Placeholder::make('travel_time')
                            ->label('Waktu Keberangkatan')
                            ->content(fn ($record) => $record->travel_time?->format('H:i') ?? '-'),

                        Forms\Components\Placeholder::make('pickup_location')
                            ->label('Lokasi Penjemputan')
                            ->content(fn ($record) => $record->pickup_location ?? '-')
                            ->columnSpan(2),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Section::make('Informasi Harga')
                    ->schema([
                        Forms\Components\Placeholder::make('total_passengers')
                            ->label('Jumlah Penumpang')
                            ->content(fn ($record) => $record->total_passengers . ' orang'),

                        Forms\Components\Placeholder::make('price_per_person')
                            ->label('Harga per Orang')
                            ->content(fn ($record) => 'Rp ' . number_format($record->price_per_person, 0, ',', '.')),

                        Forms\Components\Placeholder::make('total_price')
                            ->label('Total Harga')
                            ->content(fn ($record) => 'Rp ' . number_format($record->total_price, 0, ',', '.')),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Catatan')
                    ->schema([
                        Forms\Components\Placeholder::make('notes')
                            ->label('Catatan')
                            ->content(fn ($record) => $record->notes ?? 'Tidak ada catatan')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('whatsapp')
                ->label('Hubungi via WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(function () {
                    $adminPhone = config('app.admin_whatsapp', '6281234567890');
                    return $this->record->generateWhatsAppUrl($adminPhone);
                })
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status === 'pending'),

            Actions\Action::make('confirm')
                ->label('Konfirmasi Booking')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'confirmed']))
                ->visible(fn () => $this->record->status === 'pending'),

            Actions\EditAction::make(),
        ];
    }
}
