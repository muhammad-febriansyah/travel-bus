<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\ReportService;
use App\Models\Route;
use App\Models\Armada;
use App\Models\Category;
use Carbon\Carbon;
use Livewire\Attributes\On;

class Reports extends Page
{

    protected string $view = 'filament.pages.reports';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Laporan';
    }

    // Form state
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $quick_range = 'this_month';
    public ?int $route_id = null;
    public ?int $armada_id = null;
    public ?int $category_id = null;
    public array $status_filter = ['confirmed', 'completed'];

    // Active tab
    public string $activeTab = 'revenue';

    // Report data
    public array $revenueData = [];
    public array $analyticsData = [];
    public array $popularRoutes = [];
    public array $armadaUtilization = [];
    public array $dailyTrend = [];
    public array $timeSlots = [];

    protected ReportService $reportService;

    public function mount(): void
    {
        $this->reportService = app(ReportService::class);

        // Set default date range (this month)
        $this->applyQuickRange('this_month');

        // Load initial data
        $this->loadReportData();
    }

    public function updatedQuickRange($value): void
    {
        if ($value !== 'custom') {
            $this->applyQuickRange($value);
            $this->loadReportData();
        }
    }

    public function updatedStartDate(): void
    {
        $this->loadReportData();
    }

    public function updatedEndDate(): void
    {
        $this->loadReportData();
    }

    public function updatedRouteId(): void
    {
        $this->loadReportData();
    }

    public function updatedArmadaId(): void
    {
        $this->loadReportData();
    }

    public function updatedCategoryId(): void
    {
        $this->loadReportData();
    }

    public function updatedStatusFilter(): void
    {
        $this->loadReportData();
    }

    protected function applyQuickRange(string $range): void
    {
        $ranges = [
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'last_week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
        ];

        if (isset($ranges[$range])) {
            [$start, $end] = $ranges[$range];
            $this->start_date = $start->format('Y-m-d');
            $this->end_date = $end->format('Y-m-d');
        }
    }

    public function loadReportData(): void
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }

        $filters = [
            'status' => $this->status_filter,
            'route_id' => $this->route_id,
            'armada_id' => $this->armada_id,
            'category_id' => $this->category_id,
        ];

        // Load all report data
        $this->revenueData = $this->reportService->getRevenueReport($this->start_date, $this->end_date, $filters);
        $this->analyticsData = $this->reportService->getBookingAnalytics($this->start_date, $this->end_date);
        $this->popularRoutes = $this->reportService->getPopularRoutes($this->start_date, $this->end_date)->toArray();
        $this->armadaUtilization = $this->reportService->getArmadaUtilization($this->start_date, $this->end_date)->toArray();
        $this->dailyTrend = $this->reportService->getDailyRevenueTrend($this->start_date, $this->end_date);
        $this->timeSlots = $this->reportService->getTimeSlotAnalysis($this->start_date, $this->end_date);
    }

    public function changeTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function exportExcel(): void
    {
        // TODO: Implement Excel export
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Export Excel')
            ->body('Export Excel akan segera tersedia')
            ->send();
    }

    public function exportPdf(): void
    {
        // TODO: Implement PDF export
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Export PDF')
            ->body('Export PDF akan segera tersedia')
            ->send();
    }
}
