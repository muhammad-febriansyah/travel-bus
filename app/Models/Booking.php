<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_id',
        'route_id',
        'armada_id',
        'category_id',
        'travel_date',
        'travel_time',
        'total_passengers',
        'price_per_person',
        'total_price',
        'pickup_location',
        'notes',
        'status',
        'whatsapp_url',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'travel_time' => 'datetime',
        'total_passengers' => 'integer',
        'price_per_person' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Boot method to generate booking code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateBookingCode();
            }
        });
    }

    /**
     * Generate unique booking code
     */
    public static function generateBookingCode(): string
    {
        do {
            $code = 'BK' . date('Ymd') . strtoupper(Str::random(6));
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Get the customer that owns the booking
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the route that owns the booking
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the armada that owns the booking
     */
    public function armada(): BelongsTo
    {
        return $this->belongsTo(Armada::class);
    }

    /**
     * Get the category that owns the booking
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for completed bookings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for cancelled bookings
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('travel_date', [$startDate, $endDate]);
    }

    /**
     * Generate WhatsApp URL for admin
     */
    public function generateWhatsAppUrl($adminPhone): string
    {
        // Format phone number
        $phone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Create message
        $message = urlencode(
            "Halo Admin, saya ingin konfirmasi pemesanan:\n\n" .
            "Kode Booking: {$this->booking_code}\n" .
            "Nama: {$this->customer->name}\n" .
            "Rute: {$this->route->origin} - {$this->route->destination}\n" .
            "Tanggal: {$this->travel_date->format('d/m/Y')}\n" .
            "Jumlah Penumpang: {$this->total_passengers}\n" .
            "Total Harga: Rp " . number_format($this->total_price, 0, ',', '.')
        );

        return "https://wa.me/{$phone}?text={$message}";
    }
}
