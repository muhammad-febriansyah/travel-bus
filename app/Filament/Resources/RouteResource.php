<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RouteResource\Pages;
use App\Models\Route;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RouteResource extends Resource
{
    protected static ?string $model = Route::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map';

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Rute')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('origin')
                            ->label('Kota Asal')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Jakarta'),

                        Forms\Components\TextInput::make('destination')
                            ->label('Kota Tujuan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Bandung'),

                        Forms\Components\TextInput::make('route_code')
                            ->label('Kode Rute')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('JKT-BDG')
                            ->helperText('Contoh: JKT-BDG, SBY-MLG')
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                if (!$record && !$state) {
                                    $origin = $component->getContainer()->getComponent('origin')->getState();
                                    $destination = $component->getContainer()->getComponent('destination')->getState();
                                    if ($origin && $destination) {
                                        $code = strtoupper(substr($origin, 0, 3)) . '-' . strtoupper(substr($destination, 0, 3));
                                        $component->state($code);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('distance')
                            ->label('Jarak')
                            ->numeric()
                            ->suffix('KM')
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('Contoh: 150.5'),

                        Forms\Components\TextInput::make('estimated_duration')
                            ->label('Estimasi Waktu')
                            ->numeric()
                            ->suffix('menit')
                            ->minValue(0)
                            ->placeholder('Contoh: 180 (3 jam)')
                            ->helperText('Estimasi waktu tempuh dalam menit'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->placeholder('Rute melewati tol Cipularang...'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("No")->rowIndex(),
                Tables\Columns\TextColumn::make('route_code')
                    ->label('Kode Rute')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('origin')
                    ->label('Asal')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('success'),

                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-flag')
                    ->iconColor('danger'),

                Tables\Columns\TextColumn::make('distance')
                    ->label('Jarak')
                    ->suffix(' KM')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('estimated_duration')
                    ->label('Estimasi')
                    ->formatStateUsing(fn($state) => $state ? round($state / 60, 1) . ' jam' : '-')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('prices_count')
                    ->label('Harga')
                    ->counts('prices')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Booking')
                    ->counts('bookings')
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),

                Tables\Filters\Filter::make('origin')
                    ->schema([
                        Forms\Components\TextInput::make('origin')
                            ->label('Kota Asal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['origin'],
                            fn($query, $value) => $query->where('origin', 'like', "%{$value}%")
                        );
                    }),

                Tables\Filters\Filter::make('destination')
                    ->schema([
                        Forms\Components\TextInput::make('destination')
                            ->label('Kota Tujuan'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['destination'],
                            fn($query, $value) => $query->where('destination', 'like', "%{$value}%")
                        );
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoute::route('/create'),
            'view' => Pages\ViewRoute::route('/{record}'),
            'edit' => Pages\EditRoute::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'Rute';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Rute';
    }
}
