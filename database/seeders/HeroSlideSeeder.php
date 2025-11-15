<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Perjalanan Nyal',
                'subtitle' => '24/7 Customer Service',
                'description' => 'Layanan travel dan rental bus dengan armada modern, driver profesional, dan harga terjangkau. Nikmati perjalanan terbaik bersama kami untuk pengalaman yang tak terlupakan.',
                'badge_text' => '#1 Layanan Travel Terpercaya Sumatera Barat dan Pekanbaru',
                'primary_button_text' => 'Lihat Rute Perjalanan',
                'primary_button_url' => '#routes',
                'secondary_button_text' => 'Hubungi Kami',
                'secondary_button_url' => '#contact',
                'rating_text' => 'Kepuasan Pelanggan',
                'rating_value' => 4.9,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Armada Terbaik',
                'subtitle' => 'Modern & Nyaman',
                'description' => 'Kami menyediakan armada dengan fasilitas terlengkap untuk kenyamanan perjalanan Anda. Dilengkapi AC, reclining seat, dan entertainment system.',
                'badge_text' => 'Fleet Terawat & Bersih',
                'primary_button_text' => 'Lihat Armada',
                'primary_button_url' => '#armada',
                'secondary_button_text' => 'Pesan Sekarang',
                'secondary_button_url' => '/cek-booking',
                'rating_text' => 'Rating Armada',
                'rating_value' => 4.8,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Harga Terjangkau',
                'subtitle' => 'Promo Menarik',
                'description' => 'Dapatkan harga spesial untuk pemesanan grup dan pelanggan setia. Nikmati perjalanan berkualitas dengan budget yang hemat.',
                'badge_text' => 'Promo Spesial Bulan Ini',
                'primary_button_text' => 'Lihat Promo',
                'primary_button_url' => '#routes',
                'secondary_button_text' => 'Hubungi WhatsApp',
                'secondary_button_url' => '#contact',
                'rating_text' => 'Harga Terbaik',
                'rating_value' => 5.0,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}
