<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'site_name' => 'Travel Bisnis',
            'email' => 'info@travelbisnis.com',
            'phone' => '081234567890',
            'address' => 'Jl. Raya No. 123, Jakarta',
            'keyword' => 'travel, bus, sewa bus, rental bus, travel antar kota',
            'description' => 'Layanan travel dan rental bus terpercaya dengan armada berkualitas dan pelayanan terbaik',
        ]);
    }
}
