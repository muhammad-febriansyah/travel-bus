<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Booking')
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->default(fn () => 'BK-' . strtoupper(Str::random(8)))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('No. HP')
                                    ->tel()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ]),

                        Select::make('route_id')
                            ->label('Rute')
                            ->relationship('route', 'origin')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->origin} → {$record->destination}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Reset category when route changes
                                $set('category_id', null);
                            }),

                        Select::make('armada_id')
                            ->label('Armada')
                            ->relationship('armada', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $armada = \App\Models\Armada::find($state);
                                    if ($armada) {
                                        $set('category_id', $armada->category_id);
                                    }
                                }
                            }),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Jadwal & Penumpang')
                    ->schema([
                        DatePicker::make('travel_date')
                            ->label('Tanggal Perjalanan')
                            ->required()
                            ->native(false),

                        TimePicker::make('travel_time')
                            ->label('Waktu Keberangkatan')
                            ->native(false),

                        TextInput::make('total_passengers')
                            ->label('Jumlah Penumpang')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $pricePerPerson = $get('price_per_person') ?? 0;
                                $set('total_price', $pricePerPerson * $state);
                            }),

                        TextInput::make('pickup_location')
                            ->label('Lokasi Penjemputan'),
                    ])
                    ->columns(2),

                Section::make('Harga')
                    ->schema([
                        TextInput::make('price_per_person')
                            ->label('Harga per Orang')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $totalPassengers = $get('total_passengers') ?? 1;
                                $set('total_price', $state * $totalPassengers);
                            }),

                        TextInput::make('total_price')
                            ->label('Total Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Catatan & Status')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),

                        TextInput::make('whatsapp_url')
                            ->label('WhatsApp URL')
                            ->url()
                            ->placeholder('https://wa.me/...'),
                    ])
                    ->columns(2),
            ]);
    }
}
