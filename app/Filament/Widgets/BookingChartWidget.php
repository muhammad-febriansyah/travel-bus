<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): ?string
    {
        return 'Booking 7 Hari Terakhir';
    }

    protected function getData(): array
    {
        $data = $this->getBookingsPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Total Booking',
                    'data' => $data['bookingCounts'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getBookingsPerDay(): array
    {
        $now = now();
        $bookingCounts = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $labels[] = $date->format('d M');

            $count = Booking::whereDate('created_at', $date->format('Y-m-d'))->count();
            $bookingCounts[] = $count;
        }

        return [
            'bookingCounts' => $bookingCounts,
            'labels' => $labels,
        ];
    }
}
