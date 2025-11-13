<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'keyword',
        'email',
        'address',
        'google_maps_embed',
        'phone',
        'description',
        'logo',
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_stats',
        'features',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'whatsapp_number',
        'youtube_url',
        'tiktok_url',
        'linkedin_url',
    ];

    protected $casts = [
        'hero_stats' => 'array',
        'features' => 'array',
    ];

    /**
     * Get the settings record (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'site_name' => config('app.name'),
                'email' => '',
                'phone' => '',
                'address' => '',
                'keyword' => '',
                'description' => '',
            ]);
        }

        return $settings;
    }
}
