<?php

namespace App\Filament\Widgets;

use App\Models\Route;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PopularRoutesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Route::query()
                    ->withCount('bookings')
                    ->orderByDesc('bookings_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('route_code')
                    ->label('Kode Rute')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('origin')
                    ->label('Asal')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('success'),

                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->icon('heroicon-o-flag')
                    ->iconColor('danger'),

                Tables\Columns\TextColumn::make('distance')
                    ->label('Jarak')
                    ->suffix(' KM')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Total Booking')
                    ->badge()
                    ->color('success')
                    ->sortable(),
            ]);
    }

    public function getTableHeading(): ?string
    {
        return 'Rute Paling Populer';
    }
}
