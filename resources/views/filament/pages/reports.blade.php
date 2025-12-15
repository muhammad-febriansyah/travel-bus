<x-filament-panels::page>
    {{-- Filters Section --}}
    <x-filament::section>
        <x-slot name="heading">
            Filter Laporan
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Quick Range --}}
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Rentang Cepat
                    </x-slot>
                    <x-filament::input.select wire:model.live="quick_range">
                        <option value="today">Hari Ini</option>
                        <option value="yesterday">Kemarin</option>
                        <option value="this_week">Minggu Ini</option>
                        <option value="last_week">Minggu Lalu</option>
                        <option value="this_month">Bulan Ini</option>
                        <option value="last_month">Bulan Lalu</option>
                        <option value="this_year">Tahun Ini</option>
                        <option value="custom">Custom</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Start Date --}}
            @if($quick_range === 'custom')
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Tanggal Mulai
                    </x-slot>
                    <input
                        type="date"
                        wire:model.live="start_date"
                        class="fi-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </x-filament::input.wrapper>
            </div>

            {{-- End Date --}}
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Tanggal Akhir
                    </x-slot>
                    <input
                        type="date"
                        wire:model.live="end_date"
                        class="fi-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </x-filament::input.wrapper>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            {{-- Route Filter --}}
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Filter Rute
                    </x-slot>
                    <x-filament::input.select wire:model.live="route_id">
                        <option value="">Semua Rute</option>
                        @foreach(\App\Models\Route::all() as $route)
                            <option value="{{ $route->id }}">{{ $route->origin }} → {{ $route->destination }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Armada Filter --}}
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Filter Armada
                    </x-slot>
                    <x-filament::input.select wire:model.live="armada_id">
                        <option value="">Semua Armada</option>
                        @foreach(\App\Models\Armada::all() as $armada)
                            <option value="{{ $armada->id }}">{{ $armada->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Category Filter --}}
            <div>
                <x-filament::input.wrapper>
                    <x-slot name="label">
                        Filter Kategori
                    </x-slot>
                    <x-filament::input.select wire:model.live="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        <div class="flex gap-2 mt-6">
            <x-filament::button wire:click="loadReportData" color="primary" icon="heroicon-o-arrow-path">
                Refresh Data
            </x-filament::button>

            <x-filament::button wire:click="exportExcel" color="success" icon="heroicon-o-document-arrow-down" outlined>
                Export Excel
            </x-filament::button>

            <x-filament::button wire:click="exportPdf" color="danger" icon="heroicon-o-document-text" outlined>
                Export PDF
            </x-filament::button>
        </div>
    </x-filament::section>

    {{-- Tabs --}}
    <div class="mt-6">
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$activeTab === 'revenue'"
                wire:click="changeTab('revenue')"
            >
                Laporan Pendapatan
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'analytics'"
                wire:click="changeTab('analytics')"
            >
                Analisis Booking
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'routes'"
                wire:click="changeTab('routes')"
            >
                Performa Rute
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'armada'"
                wire:click="changeTab('armada')"
            >
                Utilisasi Armada
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    {{-- Tab Content --}}
    <div class="mt-6">
        @if($activeTab === 'revenue')
            {{-- Revenue Report Tab --}}
            <div class="space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary-600">
                                Rp {{ number_format($revenueData['total_revenue'] ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Total Pendapatan</div>
                        </div>
                    </x-filament::section>

                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-success-600">
                                {{ $revenueData['total_bookings'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Total Booking</div>
                        </div>
                    </x-filament::section>

                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-info-600">
                                Rp {{ number_format($revenueData['average_booking_value'] ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Rata-rata per Booking</div>
                        </div>
                    </x-filament::section>

                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-warning-600">
                                {{ $revenueData['total_passengers'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Total Penumpang</div>
                        </div>
                    </x-filament::section>
                </div>

                {{-- Daily Trend Chart --}}
                <x-filament::section>
                    <x-slot name="heading">
                        Tren Pendapatan Harian
                    </x-slot>

                    <div class="h-64">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </x-filament::section>

                {{-- Bookings Table --}}
                <x-filament::section>
                    <x-slot name="heading">
                        Daftar Booking ({{ count($revenueData['bookings'] ?? []) }} booking)
                    </x-slot>

                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Customer</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Rute</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Harga</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(($revenueData['bookings'] ?? []) as $booking)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $booking->booking_code }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $booking->customer->name ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $booking->route->origin ?? '-' }} → {{ $booking->route->destination ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $booking->travel_date->format('d M Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            Tidak ada data booking
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>
            </div>

        @elseif($activeTab === 'analytics')
            {{-- Analytics Tab --}}
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-success-600">
                                {{ number_format($analyticsData['conversion_rate'] ?? 0, 1) }}%
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Conversion Rate</div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $analyticsData['confirmed_bookings'] ?? 0 }} / {{ $analyticsData['total_bookings'] ?? 0 }} confirmed
                            </div>
                        </div>
                    </x-filament::section>

                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-danger-600">
                                {{ number_format($analyticsData['cancellation_rate'] ?? 0, 1) }}%
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Cancellation Rate</div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $analyticsData['cancelled_bookings'] ?? 0 }} cancelled
                            </div>
                        </div>
                    </x-filament::section>

                    <x-filament::section>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-info-600">
                                {{ number_format($analyticsData['average_lead_time'] ?? 0, 1) }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Rata-rata Lead Time (hari)</div>
                            <div class="text-xs text-gray-400 mt-1">
                                Dari booking hingga travel
                            </div>
                        </div>
                    </x-filament::section>
                </div>

                {{-- Time Slots --}}
                <x-filament::section>
                    <x-slot name="heading">
                        Analisis Waktu Keberangkatan
                    </x-slot>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($timeSlots as $slot => $data)
                            <div class="p-4 border rounded-lg">
                                <div class="font-semibold text-gray-700">{{ $data['label'] ?? ucfirst($slot) }}</div>
                                <div class="mt-2">
                                    <div class="text-2xl font-bold text-primary-600">
                                        {{ $data['count'] ?? 0 }}
                                    </div>
                                    <div class="text-sm text-gray-500">bookings</div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-lg font-semibold text-success-600">
                                        Rp {{ number_format($data['revenue'] ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-400">total revenue</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            </div>

        @elseif($activeTab === 'routes')
            {{-- Popular Routes Tab --}}
            <x-filament::section>
                <x-slot name="heading">
                    Rute Terpopuler (Top {{ count($popularRoutes) }})
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Rute</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total Revenue</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Booking</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Rata-rata Penumpang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($popularRoutes as $index => $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        {{ $item['route']->origin }} → {{ $item['route']->destination }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-right font-semibold text-primary-600">
                                        Rp {{ number_format($item['total_revenue'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-center">{{ $item['booking_count'] ?? 0 }}</td>
                                    <td class="px-4 py-2 text-sm text-center">{{ $item['average_passengers'] ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada data rute
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>

        @elseif($activeTab === 'armada')
            {{-- Armada Utilization Tab --}}
            <x-filament::section>
                <x-slot name="heading">
                    Utilisasi Armada
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Armada</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Trips</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Kursi Terisi</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total Kapasitas</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Utilisasi</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($armadaUtilization as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $item['armada']->name }}</td>
                                    <td class="px-4 py-2 text-sm text-center">{{ $item['trip_count'] }}</td>
                                    <td class="px-4 py-2 text-sm text-right">{{ $item['booked_seats'] }}</td>
                                    <td class="px-4 py-2 text-sm text-right">{{ $item['total_capacity'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-primary-600 h-2 rounded-full"
                                                     style="width: {{ $item['utilization_rate'] }}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold">{{ number_format($item['utilization_rate'], 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-right font-semibold text-success-600">
                                        Rp {{ number_format($item['total_revenue'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada data armada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>

    {{-- Chart.js Script --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($activeTab === 'revenue' && !empty($dailyTrend))
                const ctx = document.getElementById('revenueTrendChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @js($dailyTrend['dates'] ?? []),
                            datasets: [{
                                label: 'Revenue (Rp)',
                                data: @js($dailyTrend['revenue'] ?? []),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
        });

        // Reload chart when tab changes
        Livewire.on('tabChanged', () => {
            location.reload();
        });
    </script>
    @endpush
</x-filament-panels::page>
