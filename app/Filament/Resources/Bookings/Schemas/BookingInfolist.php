<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Booking')
                    ->schema([
                        TextEntry::make('booking_code')
                            ->label('Kode Booking')
                            ->copyable()
                            ->badge(),

                        TextEntry::make('customer.name')
                            ->label('Customer'),

                        TextEntry::make('customer.phone')
                            ->label('No. HP'),

                        TextEntry::make('customer.email')
                            ->label('Email'),

                        TextEntry::make('route.origin')
                            ->label('Rute Asal'),

                        TextEntry::make('route.destination')
                            ->label('Rute Tujuan'),

                        TextEntry::make('armada.name')
                            ->label('Armada'),

                        TextEntry::make('category.name')
                            ->label('Kategori'),
                    ])
                    ->columns(2),

                Section::make('Jadwal & Penumpang')
                    ->schema([
                        TextEntry::make('travel_date')
                            ->label('Tanggal Perjalanan')
                            ->date('d F Y'),

                        TextEntry::make('travel_time')
                            ->label('Waktu Keberangkatan')
                            ->time('H:i'),

                        TextEntry::make('total_passengers')
                            ->label('Jumlah Penumpang')
                            ->badge(),

                        TextEntry::make('pickup_location')
                            ->label('Lokasi Penjemputan'),
                    ])
                    ->columns(2),

                Section::make('Kursi Terpilih')
                    ->schema([
                        TextEntry::make('seatAssignments')
                            ->label('Nomor Kursi')
                            ->formatStateUsing(fn ($record) =>
                                $record->seatAssignments && $record->seatAssignments->count() > 0
                                    ? $record->seatAssignments->pluck('seat_number')->sort()->implode(', ')
                                    : 'Belum ada kursi dipilih'
                            )
                            ->badge()
                            ->color('success')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->armada && $record->armada->seatLayout)
                    ->collapsible(),

                Section::make('Harga')
                    ->schema([
                        TextEntry::make('price_per_person')
                            ->label('Harga per Orang')
                            ->money('IDR'),

                        TextEntry::make('total_price')
                            ->label('Total Harga')
                            ->money('IDR')
                            ->weight('bold'),
                    ])
                    ->columns(2),

                Section::make('Catatan & Status')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('whatsapp_url')
                            ->label('WhatsApp URL')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->placeholder('Tidak ada'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
