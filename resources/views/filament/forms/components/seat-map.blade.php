@php
    // Extract data from viewData
    $message = $message ?? null;
    $seatLayout = $seatLayout ?? null;
    $occupiedSeats = $occupiedSeats ?? [];
    $selectedSeats = $selectedSeats ?? [];
    $totalPassengers = $totalPassengers ?? 1;
    $armadaName = $armadaName ?? 'Armada';
    $capacity = $capacity ?? 0;
@endphp

<div class="seat-map-wrapper">
    @if($message)
        {{-- Show message when armada/date not selected --}}
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-8 text-center">
            <div class="text-gray-500 text-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $message }}
            </div>
        </div>
    @elseif($seatLayout && isset($seatLayout['grid']))
        {{-- Seat Map Component --}}
        <div
            x-data="{
                occupied: @js($occupiedSeats),
                selected: @js($selectedSeats),
                maxSeats: @js($totalPassengers),

                toggleSeat(seatNumber) {
                    if (this.occupied.includes(seatNumber)) {
                        return;
                    }

                    const index = this.selected.indexOf(seatNumber);
                    if (index > -1) {
                        this.selected.splice(index, 1);
                    } else {
                        if (this.selected.length >= this.maxSeats) {
                            alert('Maksimal ' + this.maxSeats + ' kursi dapat dipilih');
                            return;
                        }
                        this.selected.push(seatNumber);
                    }

                    this.updateHiddenField();
                },

                updateHiddenField() {
                    const input = document.querySelector('input[name=\\'selected_seats\\']');
                    if (input) {
                        input.value = JSON.stringify(this.selected);
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            }"
            class="space-y-4"
        >
            {{-- Header --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $armadaName }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kapasitas: {{ $capacity }} kursi</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400" x-text="selected.length"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">dari {{ $totalPassengers }} dipilih</div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="flex flex-wrap gap-3 text-xs">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded bg-green-500 border-2 border-green-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded bg-blue-500 border-2 border-blue-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">Dipilih</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded bg-red-500 border-2 border-red-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">Terisi</span>
                    </div>
                </div>
            </div>

            {{-- Seat Layout --}}
            <div class="bg-white dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                {{-- Driver --}}
                <div class="flex justify-end mb-6">
                    <div class="bg-gray-700 dark:bg-gray-600 text-white rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Supir
                    </div>
                </div>

                {{-- Seats Grid --}}
                <div class="space-y-3">
                    @foreach($seatLayout['grid'] as $row)
                        <div class="flex justify-center gap-2">
                            @foreach($row['seats'] as $seat)
                                @if($seat === null)
                                    {{-- Aisle/Empty space --}}
                                    <div class="w-14 h-14"></div>
                                @else
                                    {{-- Seat Button --}}
                                    <button
                                        type="button"
                                        @click="toggleSeat('{{ $seat }}')"
                                        :disabled="occupied.includes('{{ $seat }}')"
                                        :class="{
                                            'bg-green-500 border-green-600 hover:bg-green-600 hover:scale-105 cursor-pointer': !occupied.includes('{{ $seat }}') && !selected.includes('{{ $seat }}'),
                                            'bg-blue-500 border-blue-600 hover:bg-blue-600 hover:scale-105 cursor-pointer': selected.includes('{{ $seat }}'),
                                            'bg-red-500 border-red-600 cursor-not-allowed opacity-75': occupied.includes('{{ $seat }}')
                                        }"
                                        class="w-14 h-14 rounded-lg border-2 flex items-center justify-center text-white font-bold text-sm transition-all duration-200 active:scale-95"
                                    >
                                        {{ $seat }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Selected Seats Preview --}}
            <div
                x-show="selected.length > 0"
                x-transition
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4"
            >
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <div class="flex-1">
                        <div class="font-semibold text-blue-900 dark:text-blue-100 mb-1">Kursi yang Dipilih:</div>
                        <div class="text-blue-700 dark:text-blue-300 font-mono text-sm" x-text="selected.sort().join(', ')"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Fallback --}}
        <div class="rounded-lg border border-gray-300 p-6 text-center text-gray-500 text-sm">
            Layout kursi tidak tersedia
        </div>
    @endif
</div>
