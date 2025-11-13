<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArmadaResource\Pages;
use App\Models\Armada;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArmadaResource extends Resource
{
    protected static ?string $model = Armada::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-truck';

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Armada')
                    ->schema([
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

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Armada')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Bus Pariwisata 1, Travel Executive A'),

                        Forms\Components\TextInput::make('vehicle_type')
                            ->label('Tipe Kendaraan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Bus, Minibus, Elf, dll'),

                        Forms\Components\TextInput::make('plate_number')
                            ->label('Nomor Plat')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('B 1234 XYZ'),

                        Forms\Components\TextInput::make('capacity')
                            ->label('Kapasitas Penumpang')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Contoh: 12, 20, 45')
                            ->suffix('orang'),

                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Armada')
                            ->image()
                            ->disk('public')
                            ->directory('armadas')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ])
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Tersedia')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("No")->rowIndex(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/no-image.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Armada')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Armada $record): string => $record->vehicle_type),

                Tables\Columns\TextColumn::make('plate_number')
                    ->label('Nomor Plat')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' orang')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Total Booking')
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->placeholder('Semua')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia'),

                Tables\Filters\Filter::make('capacity')
                    ->schema([
                        Forms\Components\TextInput::make('capacity_from')
                            ->label('Kapasitas Minimal')
                            ->numeric(),
                        Forms\Components\TextInput::make('capacity_to')
                            ->label('Kapasitas Maksimal')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['capacity_from'],
                                fn($query, $value) =>
                                $query->where('capacity', '>=', $value)
                            )
                            ->when(
                                $data['capacity_to'],
                                fn($query, $value) =>
                                $query->where('capacity', '<=', $value)
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
                    BulkAction::make('toggleAvailability')
                        ->label('Toggle Ketersediaan')
                        ->icon('heroicon-o-arrow-path')
                        ->action(
                            fn($records) =>
                            $records->each(
                                fn($record) =>
                                $record->update(['is_available' => !$record->is_available])
                            )
                        )
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
            'index' => Pages\ListArmadas::route('/'),
            'create' => Pages\CreateArmada::route('/create'),
            'view' => Pages\ViewArmada::route('/{record}'),
            'edit' => Pages\EditArmada::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'Armada';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Armada';
    }
}
