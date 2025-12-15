<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SeatAvailabilityCache extends Model
{
    protected $table = 'seat_availability_cache';

    protected $fillable = [
        'armada_id',
        'travel_date',
        'travel_time',
        'occupied_seats',
        'available_count',
        'total_capacity',
        'last_updated',
    ];

    protected $casts = [
        'occupied_seats' => 'array',
        'travel_date' => 'date',
        'travel_time' => 'datetime',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the armada that owns this cache entry
     */
    public function armada(): BelongsTo
    {
        return $this->belongsTo(Armada::class);
    }

    /**
     * Check if cache is stale (older than 5 minutes)
     */
    public function isStale(): bool
    {
        return $this->last_updated->diffInMinutes(now()) > 5;
    }

    /**
     * Refresh cache for specific armada, date and time
     */
    public static function refreshCache(int $armadaId, string $date, ?string $time = null): void
    {
        $armada = Armada::find($armadaId);
        if (!$armada) {
            return;
        }

        $occupiedSeats = SeatAssignment::getOccupiedSeats($armadaId, $date, $time);
        $availableCount = $armada->capacity - count($occupiedSeats);

        static::updateOrCreate(
            [
                'armada_id' => $armadaId,
                'travel_date' => $date,
                'travel_time' => $time,
            ],
            [
                'occupied_seats' => $occupiedSeats,
                'available_count' => $availableCount,
                'total_capacity' => $armada->capacity,
                'last_updated' => now(),
            ]
        );
    }

    /**
     * Get occupied seats with caching
     */
    public static function getOccupiedSeats(int $armadaId, string $date, ?string $time = null): array
    {
        $cache = static::where('armada_id', $armadaId)
            ->where('travel_date', $date)
            ->where('travel_time', $time)
            ->first();

        if (!$cache || $cache->isStale()) {
            static::refreshCache($armadaId, $date, $time);
            $cache = static::where('armada_id', $armadaId)
                ->where('travel_date', $date)
                ->where('travel_time', $time)
                ->first();
        }

        return $cache ? $cache->occupied_seats : [];
    }

    /**
     * Get ALL occupied seats for a date (ignoring time)
     * Used for customer booking to show all occupied seats regardless of time slot
     */
    public static function getOccupiedSeatsForDate(int $armadaId, string $date): array
    {
        // Get all occupied seats for this date from all bookings (ignoring time)
        return SeatAssignment::forArmadaAndDate($armadaId, $date, null)
            ->pluck('seat_number')
            ->unique()
            ->values()
            ->toArray();
    }
}
