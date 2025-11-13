<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class LandingPageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::first();

        if ($setting) {
            $setting->update([
                'hero_badge' => '#1 Layanan Travel Terpercaya',
                'hero_title' => 'Perjalanan Nyaman, Aman & Terpercaya',
                'hero_subtitle' => 'Layanan travel dan rental bus dengan armada modern, driver profesional, dan harga terjangkau. Nikmati perjalanan terbaik bersama kami untuk pengalaman yang tak terlupakan.',
                'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666426556994!2d106.82493197499016!3d-6.175392193803947!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1699999999999!5m2!1sid!2sid',
                'hero_stats' => [
                    [
                        'number' => '10000',
                        'suffix' => '+',
                        'label' => 'Penumpang',
                    ],
                    [
                        'number' => '50',
                        'suffix' => '+',
                        'label' => 'Armada',
                    ],
                    [
                        'number' => '15',
                        'suffix' => '+',
                        'label' => 'Rute',
                    ],
                ],
                'features' => [
                    [
                        'icon' => 'Shield',
                        'title' => 'Keamanan Terjamin',
                        'description' => 'Armada dilengkapi dengan asuransi perjalanan dan sistem keamanan modern untuk kenyamanan Anda.',
                        'rating' => '4.9',
                    ],
                    [
                        'icon' => 'Clock',
                        'title' => 'Tepat Waktu',
                        'description' => 'Kami berkomitmen untuk selalu on-time dengan jadwal keberangkatan yang teratur dan terpercaya.',
                        'rating' => '4.8',
                    ],
                    [
                        'icon' => 'DollarSign',
                        'title' => 'Harga Terjangkau',
                        'description' => 'Dapatkan harga terbaik dengan berbagai pilihan paket yang sesuai dengan budget Anda.',
                        'rating' => '4.7',
                    ],
                    [
                        'icon' => 'Users',
                        'title' => 'Driver Profesional',
                        'description' => 'Driver berpengalaman dan terlatih untuk memastikan perjalanan Anda aman dan nyaman.',
                        'rating' => '4.9',
                    ],
                    [
                        'icon' => 'Headphones',
                        'title' => 'Layanan 24/7',
                        'description' => 'Customer service kami siap membantu Anda kapan saja untuk kebutuhan perjalanan Anda.',
                        'rating' => '4.8',
                    ],
                    [
                        'icon' => 'Star',
                        'title' => 'Armada Terawat',
                        'description' => 'Kendaraan selalu dalam kondisi prima dengan perawatan rutin dan fasilitas lengkap.',
                        'rating' => '4.9',
                    ],
                ],
                'facebook_url' => 'https://facebook.com/travelbisnis',
                'instagram_url' => 'https://instagram.com/travelbisnis',
                'twitter_url' => 'https://twitter.com/travelbisnis',
                'whatsapp_number' => '6281234567890',
                'youtube_url' => 'https://youtube.com/@travelbisnis',
                'tiktok_url' => 'https://tiktok.com/@travelbisnis',
                'linkedin_url' => 'https://linkedin.com/company/travelbisnis',
            ]);
        }
    }
}
