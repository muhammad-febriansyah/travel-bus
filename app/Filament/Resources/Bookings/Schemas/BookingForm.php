<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use App\Models\Armada;
use App\Models\SeatAvailabilityCache;

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
                            ->default(fn() => 'BK-' . strtoupper(Str::random(8)))
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
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->origin} → {$record->destination}")
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
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                                if ($state) {
                                    $armada = \App\Models\Armada::find($state);
                                    if ($armada) {
                                        $set('category_id', $armada->category_id);
                                    }
                                }
                                // Reset selected seats when armada changes
                                $set('selected_seats', '[]');

                                // Force refresh of seat map
                                $set('seat_map', null);
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
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset selected seats when date changes
                                $set('selected_seats', '[]');

                                // Force refresh of seat map
                                $set('seat_map', null);
                            }),

                        TimePicker::make('travel_time')
                            ->label('Waktu Keberangkatan')
                            ->native(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset selected seats when time changes
                                $set('selected_seats', '[]');
                            }),

                        TextInput::make('total_passengers')
                            ->label('Jumlah Penumpang')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $pricePerPerson = $get('price_per_person') ?? 0;
                                $set('total_price', $pricePerPerson * $state);

                                // Adjust selected seats if total passengers changed
                                $selectedSeats = json_decode($get('selected_seats') ?? '[]', true);
                                if (count($selectedSeats) > $state) {
                                    // Remove excess seats
                                    $selectedSeats = array_slice($selectedSeats, 0, $state);
                                    $set('selected_seats', json_encode($selectedSeats));
                                }
                            }),

                        TextInput::make('pickup_location')
                            ->label('Lokasi Penjemputan'),
                    ])
                    ->columns(2),

                Section::make('Pilih Kursi (Seperti Pesawat)')
                    ->description('Pilih kursi secara visual untuk setiap penumpang')
                    ->schema([
                        // Hidden field to store selected seats as JSON
                        TextInput::make('selected_seats')
                            ->label('Kursi Terpilih')
                            ->hidden()
                            ->dehydrated()
                            ->afterStateHydrated(function ($component, $state) {
                                // When editing, load existing seat assignments
                                if (!$state && $component->getRecord()) {
                                    $booking = $component->getRecord();
                                    if ($booking && $booking->seatAssignments) {
                                        $seats = $booking->seatAssignments->pluck('seat_number')->toArray();
                                        $component->state(json_encode($seats));
                                    }
                                }
                            }),

                        // Visual seat map component
                        ViewField::make('seat_map')
                            ->label('')
                            ->view('filament.forms.components.seat-map')
                            ->viewData(function ($get) {
                                $armadaId = $get('armada_id');
                                $travelDate = $get('travel_date');
                                $travelTime = $get('travel_time');

                                if (!$armadaId || !$travelDate) {
                                    return ['message' => 'Pilih armada dan tanggal terlebih dahulu'];
                                }

                                $armada = Armada::with('seatLayout')->find($armadaId);

                                if (!$armada || !$armada->seatLayout) {
                                    return ['message' => 'Armada tidak memiliki layout kursi'];
                                }

                                $occupiedSeats = SeatAvailabilityCache::getOccupiedSeats(
                                    $armadaId,
                                    $travelDate,
                                    $travelTime
                                );

                                $selectedSeats = json_decode($get('selected_seats') ?? '[]', true);

                                return [
                                    'seatLayout' => $armada->seatLayout->seat_map_config,
                                    'occupiedSeats' => $occupiedSeats,
                                    'selectedSeats' => $selectedSeats,
                                    'totalPassengers' => $get('total_passengers') ?? 1,
                                    'armadaName' => $armada->name,
                                    'capacity' => $armada->capacity,
                                    'armadaId' => $armadaId,
                                    'travelDate' => $travelDate,
                                ];
                            })
                            ->columnSpanFull()
                            ->dehydrated(false),


                        // Preview selected seats
                        Placeholder::make('selected_seats_display')
                            ->label('✓ Kursi yang Dipilih')
                            ->content(function ($get) {
                                $seats = json_decode($get('selected_seats') ?? '[]', true);
                                if (empty($seats)) {
                                    return 'Belum ada kursi dipilih';
                                }
                                sort($seats);
                                return '🪑 ' . implode(', ', $seats);
                            })
                            ->visible(fn ($get) => !empty(json_decode($get('selected_seats') ?? '[]', true))),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(false),

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
