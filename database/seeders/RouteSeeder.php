<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            [
                'origin' => 'Jakarta',
                'destination' => 'Bandung',
                'route_code' => 'JKT-BDG',
                'distance' => 150,
                'estimated_duration' => 180,
                'is_active' => true,
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Semarang',
                'route_code' => 'JKT-SMG',
                'distance' => 450,
                'estimated_duration' => 480,
                'is_active' => true,
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Surabaya',
                'route_code' => 'JKT-SBY',
                'distance' => 780,
                'estimated_duration' => 720,
                'is_active' => true,
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Yogyakarta',
                'route_code' => 'JKT-YOG',
                'distance' => 560,
                'estimated_duration' => 540,
                'is_active' => true,
            ],
            [
                'origin' => 'Bandung',
                'destination' => 'Surabaya',
                'route_code' => 'BDG-SBY',
                'distance' => 680,
                'estimated_duration' => 660,
                'is_active' => true,
            ],
            [
                'origin' => 'Bandung',
                'destination' => 'Yogyakarta',
                'route_code' => 'BDG-YOG',
                'distance' => 420,
                'estimated_duration' => 420,
                'is_active' => true,
            ],
            [
                'origin' => 'Semarang',
                'destination' => 'Surabaya',
                'route_code' => 'SMG-SBY',
                'distance' => 330,
                'estimated_duration' => 300,
                'is_active' => true,
            ],
            [
                'origin' => 'Yogyakarta',
                'destination' => 'Surabaya',
                'route_code' => 'YOG-SBY',
                'distance' => 320,
                'estimated_duration' => 300,
                'is_active' => true,
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Malang',
                'route_code' => 'JKT-MLG',
                'distance' => 820,
                'estimated_duration' => 780,
                'is_active' => true,
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Solo',
                'route_code' => 'JKT-SLO',
                'distance' => 600,
                'estimated_duration' => 570,
                'is_active' => true,
            ],
        ];

        foreach ($routes as $route) {
            Route::create($route);
        }
    }
}
