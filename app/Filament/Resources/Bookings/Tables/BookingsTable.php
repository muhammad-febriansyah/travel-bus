<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),

                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->customer?->phone),

                TextColumn::make('route.origin')
                    ->label('Rute')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->route ? "{$record->route->origin} → {$record->route->destination}" : '-'),

                TextColumn::make('armada.name')
                    ->label('Armada')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('travel_date')
                    ->label('Tanggal Perjalanan')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('total_passengers')
                    ->label('Penumpang')
                    ->numeric()
                    ->suffix(' org')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('route_id')
                    ->label('Rute')
                    ->relationship('route', 'origin')
                    ->preload(),

                SelectFilter::make('armada_id')
                    ->label('Armada')
                    ->relationship('armada', 'name')
                    ->preload(),

                Filter::make('travel_date')
                    ->form([
                        Forms\Components\DatePicker::make('travel_from')
                            ->label('Tanggal Dari'),
                        Forms\Components\DatePicker::make('travel_to')
                            ->label('Tanggal Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['travel_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('travel_date', '>=', $date),
                            )
                            ->when(
                                $data['travel_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('travel_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                // Quick action: Konfirmasi Booking
                Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check')
                    ->color('primary')
                    ->size('sm')
                    ->button()
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Booking?')
                    ->modalDescription(fn($record) => "Konfirmasi booking {$record->booking_code} untuk {$record->customer->name}?")
                    ->action(function ($record) {
                        $record->update(['status' => 'confirmed']);

                        Notification::make()
                            ->success()
                            ->title('Booking Dikonfirmasi')
                            ->body("Booking {$record->booking_code} berhasil dikonfirmasi.")
                            ->send();
                    }),

                // Quick action: Tandai Selesai
                Action::make('complete')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->size('sm')
                    ->button()
                    ->visible(fn($record) => in_array($record->status, ['pending', 'confirmed']))
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Trip Selesai?')
                    ->modalDescription(fn($record) => "Trip untuk booking {$record->booking_code} sudah sampai tujuan? Kursi akan dilepas dan tersedia untuk booking lain.")
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);

                        // Invalidate cache to release seats
                        \App\Models\SeatAvailabilityCache::where('armada_id', $record->armada_id)
                            ->where('travel_date', $record->travel_date->format('Y-m-d'))
                            ->delete();

                        Notification::make()
                            ->success()
                            ->title('Trip Selesai')
                            ->body("Booking {$record->booking_code} selesai. Kursi sudah tersedia untuk booking lain.")
                            ->send();
                    }),

                // EditAction disabled - use React Seat Booking instead
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
