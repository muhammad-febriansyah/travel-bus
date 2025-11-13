<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'route_code',
        'distance',
        'estimated_duration',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'distance' => 'decimal:2',
        'estimated_duration' => 'integer',
    ];

    /**
     * Get all prices for this route
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get all bookings for this route
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope for active routes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get route display name
     */
    public function getRouteNameAttribute(): string
    {
        return "{$this->origin} - {$this->destination}";
    }
}
