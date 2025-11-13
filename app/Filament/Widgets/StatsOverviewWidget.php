<?php

namespace App\Filament\Widgets;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Route;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Total booking hari ini
        $todayBookings = Booking::whereDate('created_at', today())->count();

        // Total revenue bulan ini
        $monthlyRevenue = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price');

        // Pending bookings
        $pendingBookings = Booking::where('status', 'pending')->count();

        // Total customers
        $totalCustomers = Customer::count();

        // Active routes
        $activeRoutes = Route::where('is_active', true)->count();

        // Available armadas
        $availableArmadas = Armada::where('is_available', true)->count();

        return [
            Stat::make('Booking Hari Ini', $todayBookings)
                ->description('Total booking yang masuk hari ini')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, $todayBookings]),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description('Revenue dari booking confirmed & completed')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Booking Pending', $pendingBookings)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.bookings.index', ['tableFilters' => ['status' => ['values' => ['pending']]]])),

            Stat::make('Total Pelanggan', $totalCustomers)
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),

            Stat::make('Rute Aktif', $activeRoutes)
                ->description('Rute yang tersedia')
                ->descriptionIcon('heroicon-o-map')
                ->color('primary'),

            Stat::make('Armada Tersedia', $availableArmadas)
                ->description('Armada siap operasional')
                ->descriptionIcon('heroicon-o-truck')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
