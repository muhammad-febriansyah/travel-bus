<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Route;
use App\Models\Armada;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ReportService
{
    /**
     * Get revenue report data
     */
    public function getRevenueReport(string $startDate, string $endDate, array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('revenue', $startDate, $endDate, $filters);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate, $filters) {
            $query = Booking::query()
                ->whereBetween('travel_date', [$startDate, $endDate])
                ->whereIn('status', $filters['status'] ?? ['confirmed', 'completed']);

            if (!empty($filters['route_id'])) {
                $query->where('route_id', $filters['route_id']);
            }

            if (!empty($filters['armada_id'])) {
                $query->where('armada_id', $filters['armada_id']);
            }

            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            $bookings = $query->with(['customer', 'route', 'armada', 'category'])->get();

            return [
                'total_revenue' => $bookings->sum('total_price'),
                'total_bookings' => $bookings->count(),
                'average_booking_value' => $bookings->avg('total_price') ?? 0,
                'total_passengers' => $bookings->sum('total_passengers'),
                'bookings' => $bookings,
                'revenue_by_status' => $bookings->groupBy('status')->map(fn($group) => [
                    'count' => $group->count(),
                    'revenue' => $group->sum('total_price'),
                ]),
            ];
        });
    }

    /**
     * Get booking analytics
     */
    public function getBookingAnalytics(string $startDate, string $endDate): array
    {
        $cacheKey = $this->getCacheKey('analytics', $startDate, $endDate);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate) {
            $totalBookings = Booking::whereBetween('travel_date', [$startDate, $endDate])->count();

            $confirmedBookings = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->whereIn('status', ['confirmed', 'completed'])
                ->count();

            $cancelledBookings = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->where('status', 'cancelled')
                ->count();

            $pendingBookings = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->where('status', 'pending')
                ->count();

            $averageLeadTime = DB::table('bookings')
                ->whereBetween('travel_date', [$startDate, $endDate])
                ->selectRaw('AVG(DATEDIFF(travel_date, created_at)) as avg_days')
                ->value('avg_days') ?? 0;

            // Repeat customer analysis
            $customersWithMultipleBookings = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->select('customer_id')
                ->groupBy('customer_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            $totalCustomers = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->distinct('customer_id')
                ->count('customer_id');

            return [
                'total_bookings' => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'cancelled_bookings' => $cancelledBookings,
                'pending_bookings' => $pendingBookings,
                'conversion_rate' => $totalBookings > 0 ? ($confirmedBookings / $totalBookings) * 100 : 0,
                'cancellation_rate' => $totalBookings > 0 ? ($cancelledBookings / $totalBookings) * 100 : 0,
                'average_lead_time' => round($averageLeadTime, 1),
                'repeat_customer_rate' => $totalCustomers > 0 ? ($customersWithMultipleBookings / $totalCustomers) * 100 : 0,
                'total_customers' => $totalCustomers,
                'repeat_customers' => $customersWithMultipleBookings,
            ];
        });
    }

    /**
     * Get popular routes report
     */
    public function getPopularRoutes(string $startDate, string $endDate, int $limit = 10): Collection
    {
        $cacheKey = $this->getCacheKey('popular_routes', $startDate, $endDate, ['limit' => $limit]);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate, $limit) {
            return Route::query()
                ->withCount(['bookings' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate]);
                }])
                ->withSum(['bookings as revenue' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate])
                        ->whereIn('status', ['confirmed', 'completed']);
                }], 'total_price')
                ->withAvg(['bookings as avg_passengers' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate]);
                }], 'total_passengers')
                ->having('bookings_count', '>', 0)
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get()
                ->map(function ($route) {
                    return [
                        'route' => $route,
                        'booking_count' => $route->bookings_count,
                        'total_revenue' => $route->revenue ?? 0,
                        'average_passengers' => round($route->avg_passengers ?? 0, 1),
                    ];
                });
        });
    }

    /**
     * Get armada utilization report
     */
    public function getArmadaUtilization(string $startDate, string $endDate): Collection
    {
        $cacheKey = $this->getCacheKey('armada_util', $startDate, $endDate);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate) {
            $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

            return Armada::query()
                ->withSum(['bookings as total_passengers' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate])
                        ->whereIn('status', ['confirmed', 'completed']);
                }], 'total_passengers')
                ->withCount(['bookings as trip_count' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate])
                        ->whereIn('status', ['confirmed', 'completed']);
                }])
                ->withSum(['bookings as total_revenue' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('travel_date', [$startDate, $endDate])
                        ->whereIn('status', ['confirmed', 'completed']);
                }], 'total_price')
                ->get()
                ->map(function ($armada) use ($days) {
                    $totalCapacity = $armada->capacity * $days;
                    $bookedSeats = $armada->total_passengers ?? 0;
                    $utilizationRate = $totalCapacity > 0 ? ($bookedSeats / $totalCapacity) * 100 : 0;

                    return [
                        'armada' => $armada,
                        'trip_count' => $armada->trip_count,
                        'booked_seats' => $bookedSeats,
                        'total_capacity' => $totalCapacity,
                        'utilization_rate' => round($utilizationRate, 2),
                        'total_revenue' => $armada->total_revenue ?? 0,
                        'average_revenue_per_trip' => $armada->trip_count > 0 ? ($armada->total_revenue ?? 0) / $armada->trip_count : 0,
                    ];
                })
                ->sortByDesc('utilization_rate');
        });
    }

    /**
     * Get daily revenue trend
     */
    public function getDailyRevenueTrend(string $startDate, string $endDate): array
    {
        $cacheKey = $this->getCacheKey('daily_trend', $startDate, $endDate);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate) {
            $data = DB::table('bookings')
                ->select(
                    DB::raw('DATE(travel_date) as date'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as booking_count')
                )
                ->whereBetween('travel_date', [$startDate, $endDate])
                ->whereIn('status', ['confirmed', 'completed'])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'dates' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'booking_counts' => $data->pluck('booking_count')->toArray(),
            ];
        });
    }

    /**
     * Get time slot analysis
     */
    public function getTimeSlotAnalysis(string $startDate, string $endDate): array
    {
        $cacheKey = $this->getCacheKey('time_slots', $startDate, $endDate);

        return Cache::remember($cacheKey, 900, function () use ($startDate, $endDate) {
            $bookings = Booking::whereBetween('travel_date', [$startDate, $endDate])
                ->whereNotNull('travel_time')
                ->get();

            $morning = $bookings->filter(function ($booking) {
                $hour = Carbon::parse($booking->travel_time)->hour;
                return $hour >= 0 && $hour < 12;
            });

            $afternoon = $bookings->filter(function ($booking) {
                $hour = Carbon::parse($booking->travel_time)->hour;
                return $hour >= 12 && $hour < 18;
            });

            $evening = $bookings->filter(function ($booking) {
                $hour = Carbon::parse($booking->travel_time)->hour;
                return $hour >= 18 && $hour < 24;
            });

            return [
                'morning' => [
                    'count' => $morning->count(),
                    'revenue' => $morning->sum('total_price'),
                    'label' => 'Pagi (00:00-12:00)',
                ],
                'afternoon' => [
                    'count' => $afternoon->count(),
                    'revenue' => $afternoon->sum('total_price'),
                    'label' => 'Siang (12:00-18:00)',
                ],
                'evening' => [
                    'count' => $evening->count(),
                    'revenue' => $evening->sum('total_price'),
                    'label' => 'Malam (18:00-24:00)',
                ],
            ];
        });
    }

    /**
     * Generate cache key
     */
    protected function getCacheKey(string $type, string $startDate, string $endDate, array $filters = []): string
    {
        $filterHash = md5(json_encode($filters));
        return "report:{$type}:{$startDate}:{$endDate}:{$filterHash}";
    }

    /**
     * Clear all report caches
     */
    public function clearCache(): void
    {
        Cache::tags(['reports'])->flush();
    }
}
