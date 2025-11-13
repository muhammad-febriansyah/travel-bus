<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceResource\Pages;
use App\Models\Price;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Harga')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('route_id')
                            ->label('Rute')
                            ->relationship('route', 'route_code')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->route_code} - {$record->origin} ke {$record->destination}")
                            ->createOptionForm([
                                Forms\Components\TextInput::make('origin')
                                    ->label('Kota Asal')
                                    ->required(),
                                Forms\Components\TextInput::make('destination')
                                    ->label('Kota Tujuan')
                                    ->required(),
                                Forms\Components\TextInput::make('route_code')
                                    ->label('Kode Rute')
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                            ]),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1000)
                            ->placeholder('100000'),

                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->step(1000)
                            ->placeholder('Contoh: 10000, 25000')
                            ->helperText('Masukkan nilai diskon dalam rupiah'),

                        Forms\Components\DatePicker::make('valid_from')
                            ->label('Berlaku Dari')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->helperText('Kosongkan jika berlaku sejak sekarang'),

                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Berlaku Sampai')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->helperText('Kosongkan jika berlaku selamanya')
                            ->after('valid_from'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Placeholder::make('final_price_preview')
                            ->label('Harga Setelah Diskon')
                            ->content(function (Get $get) {
                                $price = (float) ($get('price') ?? 0);
                                $discount = (float) ($get('discount') ?? 0);
                                $final = $price - $discount;
                                return 'Rp ' . number_format($final, 0, ',', '.');
                            }),
                    ])
                    ->columnSpanFull()
                    ->hidden(fn(string $operation) => $operation === 'view'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("No")->rowIndex(),
                Tables\Columns\TextColumn::make('route.route_code')
                    ->label('Kode Rute')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('route.origin')
                    ->label('Asal')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('route.destination')
                    ->label('Tujuan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Diskon')
                    ->money('IDR')
                    ->color('danger')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('final_price')
                    ->label('Harga Final')
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->description(fn(Price $record) => $record->discount > 0 ? "Hemat Rp " . number_format($record->discount, 0, ',', '.') : null),

                Tables\Columns\TextColumn::make('valid_from')
                    ->label('Berlaku Dari')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('route_id')
                    ->label('Rute')
                    ->relationship('route', 'route_code')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),

                Tables\Filters\Filter::make('has_discount')
                    ->label('Dengan Diskon')
                    ->query(fn($query) => $query->where('discount', '>', 0)),

                Tables\Filters\Filter::make('valid_now')
                    ->label('Valid Sekarang')
                    ->query(function ($query) {
                        return $query->where(function ($q) {
                            $q->where('valid_from', '<=', now())
                                ->orWhereNull('valid_from');
                        })->where(function ($q) {
                            $q->where('valid_until', '>=', now())
                                ->orWhereNull('valid_until');
                        });
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
            'index' => Pages\ListPrices::route('/'),
            'create' => Pages\CreatePrice::route('/create'),
            'view' => Pages\ViewPrice::route('/{record}'),
            'edit' => Pages\EditPrice::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'Harga';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Harga';
    }
}
