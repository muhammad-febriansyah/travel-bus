<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    protected static string | \UnitEnum | null $navigationGroup = 'Data Pelanggan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pelanggan')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->required()
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->required()
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ]),
                    ]),

                Section::make('Detail Perjalanan')
                    ->schema([
                        Forms\Components\Select::make('route_id')
                            ->label('Rute')
                            ->relationship('route', 'route_code')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->route_code} - {$record->origin} ke {$record->destination}"),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $routeId = $get('route_id');
                                if ($state && $routeId) {
                                    $price = \App\Models\Price::where('route_id', $routeId)
                                        ->where('category_id', $state)
                                        ->where('is_active', true)
                                        ->first();

                                    if ($price) {
                                        $finalPrice = $price->final_price;
                                        $set('price_per_person', $finalPrice);
                                        $passengers = $get('total_passengers') ?? 1;
                                        $set('total_price', $finalPrice * $passengers);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('armada_id')
                            ->label('Armada')
                            ->relationship(
                                'armada',
                                'name',
                                fn(Builder $query, Get $get) =>
                                $query->when(
                                    $get('category_id'),
                                    fn($q, $catId) =>
                                    $q->where('category_id', $catId)
                                )->where('is_available', true)
                            )
                            ->required()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->plate_number}) - Kapasitas: {$record->capacity}"),

                        Forms\Components\DatePicker::make('travel_date')
                            ->label('Tanggal Keberangkatan')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now()),

                        Forms\Components\TimePicker::make('travel_time')
                            ->label('Jam Keberangkatan')
                            ->seconds(false),

                        Forms\Components\TextInput::make('pickup_location')
                            ->label('Lokasi Penjemputan')
                            ->maxLength(255)
                            ->placeholder('Alamat penjemputan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Detail Harga')
                    ->schema([
                        Forms\Components\TextInput::make('total_passengers')
                            ->label('Jumlah Penumpang')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->placeholder('Contoh: 1, 2, 5')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $pricePerPerson = $get('price_per_person') ?? 0;
                                $set('total_price', $pricePerPerson * $state);
                            }),

                        Forms\Components\TextInput::make('price_per_person')
                            ->label('Harga per Orang')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('Akan terisi otomatis setelah pilih rute & kategori')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $passengers = $get('total_passengers') ?? 1;
                                $set('total_price', $state * $passengers);
                            }),

                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),
                    ])
                    ->columns(3),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Contoh: Butuh kursi roda, Bawa anak kecil, Alergi makanan tertentu'),

                        Forms\Components\Placeholder::make('booking_code_info')
                            ->label('Kode Booking')
                            ->content(fn($record) => $record?->booking_code ?? 'Akan digenerate otomatis')
                            ->hidden(fn(string $operation) => $operation === 'create'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Booking $record) => $record->customer->phone),

                Tables\Columns\TextColumn::make('route.route_code')
                    ->label('Rute')
                    ->badge()
                    ->color('info')
                    ->description(fn(Booking $record) => "{$record->route->origin} → {$record->route->destination}"),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('armada.name')
                    ->label('Armada')
                    ->toggleable()
                    ->description(fn(Booking $record) => $record->armada->plate_number),

                Tables\Columns\TextColumn::make('travel_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn(Booking $record) => $record->travel_time?->format('H:i')),

                Tables\Columns\TextColumn::make('total_passengers')
                    ->label('Penumpang')
                    ->suffix(' org')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('route_id')
                    ->label('Rute')
                    ->relationship('route', 'route_code')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\Filter::make('travel_date')
                    ->schema([
                        Forms\Components\DatePicker::make('travel_date_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('travel_date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['travel_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('travel_date', '>=', $date),
                            )
                            ->when(
                                $data['travel_date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('travel_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->action(function (Booking $record) {
                        $adminPhone = config('app.admin_whatsapp', '6281234567890');
                        $url = $record->generateWhatsAppUrl($adminPhone);
                        $record->update(['whatsapp_url' => $url]);
                        return redirect($url);
                    })
                    ->visible(fn(Booking $record) => $record->status === 'pending'),

                Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->button()
                    ->form([
                        Forms\Components\TextInput::make('travel_time')
                            ->label('Jam Keberangkatan')
                            ->required()
                            ->type('time')
                            ->placeholder('08:00')
                            ->default(fn(Booking $record) => $record->travel_time?->format('H:i')),

                        Forms\Components\TextInput::make('pickup_location')
                            ->label('Lokasi Penjemputan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Jl. Merdeka No. 123, Jakarta Pusat')
                            ->default(fn(Booking $record) => $record->pickup_location),
                    ])
                    ->modalHeading('Konfirmasi Booking')
                    ->modalDescription('Tentukan jam keberangkatan dan lokasi penjemputan untuk booking ini.')
                    ->modalSubmitActionLabel('Konfirmasi Booking')
                    ->modalWidth('md')
                    ->action(function (Booking $record, array $data) {
                        $record->update([
                            'status' => 'confirmed',
                            'travel_time' => $data['travel_time'],
                            'pickup_location' => $data['pickup_location'],
                        ]);
                    })
                    ->visible(fn(Booking $record) => $record->status === 'pending'),

                Action::make('complete')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Selesai')
                    ->modalDescription('Apakah Anda yakin perjalanan sudah selesai?')
                    ->modalSubmitActionLabel('Ya, Selesai')
                    ->action(fn(Booking $record) => $record->update(['status' => 'completed']))
                    ->visible(fn(Booking $record) => $record->status === 'confirmed'),

                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Booking')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan booking ini?')
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(fn(Booking $record) => $record->update(['status' => 'cancelled']))
                    ->visible(fn(Booking $record) => in_array($record->status, ['pending', 'confirmed'])),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    BulkAction::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each->update(['status' => 'confirmed']))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each->update(['status' => 'cancelled']))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'booking_code',
            'customer.name',
            'customer.phone',
            'route.route_code',
            'route.origin',
            'route.destination',
        ];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->booking_code;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Pelanggan' => $record->customer->name,
            'Rute' => "{$record->route->origin} → {$record->route->destination}",
            'Tanggal' => $record->travel_date->format('d M Y'),
            'Status' => ucfirst($record->status),
        ];
    }

    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return BookingResource::getUrl('view', ['record' => $record]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getLabel(): ?string
    {
        return 'Pemesanan';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Pemesanan';
    }
}
