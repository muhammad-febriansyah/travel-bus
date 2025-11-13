<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'category_id',
        'price',
        'discount',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the route that owns the price
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the category that owns the price
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for active prices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid prices on a specific date
     */
    public function scopeValidOn($query, $date)
    {
        return $query->where(function ($q) use ($date) {
            $q->where('valid_from', '<=', $date)
              ->orWhereNull('valid_from');
        })->where(function ($q) use ($date) {
            $q->where('valid_until', '>=', $date)
              ->orWhereNull('valid_until');
        });
    }

    /**
     * Get final price after discount
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->price - $this->discount;
    }
}
