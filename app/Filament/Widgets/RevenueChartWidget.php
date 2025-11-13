<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    public function getHeading(): ?string
    {
        return 'Pendapatan 7 Hari Terakhir';
    }

    protected function getData(): array
    {
        $data = $this->getRevenuePerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data['revenues'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getRevenuePerDay(): array
    {
        $now = now();
        $revenues = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $labels[] = $date->format('d M');

            $revenue = Booking::whereDate('created_at', $date->format('Y-m-d'))
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('total_price');

            $revenues[] = $revenue;
        }

        return [
            'revenues' => $revenues,
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'callback' => "function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }",
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) { return 'Rp ' + context.parsed.y.toLocaleString('id-ID'); }",
                    ],
                ],
            ],
        ];
    }
}
