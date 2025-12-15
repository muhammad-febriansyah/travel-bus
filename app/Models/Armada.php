<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Armada extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'seat_layout_id',
        'name',
        'vehicle_type',
        'plate_number',
        'capacity',
        'description',
        'image',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get the category that owns the armada
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all bookings for this armada
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope for available armadas
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the seat layout for this armada
     */
    public function seatLayout(): BelongsTo
    {
        return $this->belongsTo(SeatLayout::class);
    }

    /**
     * Get seat map configuration from layout
     */
    public function getSeatMapAttribute()
    {
        if ($this->seatLayout) {
            return $this->seatLayout->seat_map_config;
        }

        return null;
    }

    /**
     * Get available seats for a specific date and time
     */
    public function getAvailableSeats(string $date, ?string $time = null): array
    {
        if (!$this->seatLayout) {
            return [];
        }

        $allSeats = $this->seatLayout->seat_numbers;
        $occupiedSeats = SeatAvailabilityCache::getOccupiedSeats($this->id, $date, $time);

        return array_diff($allSeats, $occupiedSeats);
    }

    /**
     * Get total number of seats
     */
    public function getTotalSeats(): int
    {
        return $this->capacity ?? 0;
    }
}
