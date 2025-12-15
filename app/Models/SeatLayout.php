<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatLayout extends Model
{
    protected $fillable = [
        'capacity',
        'rows',
        'columns',
        'layout_type',
        'seat_map_config',
    ];

    protected $casts = [
        'seat_map_config' => 'array',
    ];

    /**
     * Get armadas using this seat layout
     */
    public function armadas(): HasMany
    {
        return $this->hasMany(Armada::class);
    }

    /**
     * Get flat array of all seat numbers from the layout
     */
    public function getSeatNumbersAttribute(): array
    {
        $seatNumbers = [];

        if (isset($this->seat_map_config['grid']) && is_array($this->seat_map_config['grid'])) {
            foreach ($this->seat_map_config['grid'] as $row) {
                if (isset($row['seats']) && is_array($row['seats'])) {
                    foreach ($row['seats'] as $seat) {
                        if ($seat !== null) {
                            $seatNumbers[] = $seat;
                        }
                    }
                }
            }
        }

        return $seatNumbers;
    }

    /**
     * Get rows and columns configuration
     */
    public function getRowsAndColumns(): array
    {
        return [
            'rows' => $this->rows,
            'columns' => $this->columns,
            'total_seats' => $this->capacity,
        ];
    }
}
