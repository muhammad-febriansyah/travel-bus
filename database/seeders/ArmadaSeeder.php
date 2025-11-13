<?php

namespace Database\Seeders;

use App\Models\Armada;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArmadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eksekutif = Category::where('slug', 'eksekutif')->first();
        $ekonomi = Category::where('slug', 'ekonomi')->first();

        $armadas = [
            // Armada Eksekutif
            [
                'category_id' => $eksekutif->id,
                'name' => 'Mercedes-Benz OH 1526',
                'vehicle_type' => 'Bus',
                'plate_number' => 'B 1234 ABC',
                'capacity' => 35,
                'description' => 'Bus eksekutif dengan AC, reclining seat, dan fasilitas premium',
                'is_available' => true,
            ],
            [
                'category_id' => $eksekutif->id,
                'name' => 'Scania K410',
                'vehicle_type' => 'Bus',
                'plate_number' => 'B 2345 DEF',
                'capacity' => 40,
                'description' => 'Bus eksekutif mewah dengan toilet, TV, dan WiFi',
                'is_available' => true,
            ],
            [
                'category_id' => $eksekutif->id,
                'name' => 'Hino RK8',
                'vehicle_type' => 'Bus',
                'plate_number' => 'B 3456 GHI',
                'capacity' => 35,
                'description' => 'Bus eksekutif dengan seat 2-2, AC, dan charging port',
                'is_available' => true,
            ],

            // Armada Ekonomi
            [
                'category_id' => $ekonomi->id,
                'name' => 'Mitsubishi Colt Diesel',
                'vehicle_type' => 'Minibus',
                'plate_number' => 'B 4567 JKL',
                'capacity' => 20,
                'description' => 'Minibus ekonomi dengan AC dan seat nyaman',
                'is_available' => true,
            ],
            [
                'category_id' => $ekonomi->id,
                'name' => 'Isuzu Elf',
                'vehicle_type' => 'Minibus',
                'plate_number' => 'B 5678 MNO',
                'capacity' => 18,
                'description' => 'Minibus ekonomi untuk perjalanan jarak menengah',
                'is_available' => true,
            ],
            [
                'category_id' => $ekonomi->id,
                'name' => 'Toyota HiAce',
                'vehicle_type' => 'Minibus',
                'plate_number' => 'B 6789 PQR',
                'capacity' => 15,
                'description' => 'Minibus ekonomi compact dengan AC',
                'is_available' => true,
            ],
            [
                'category_id' => $ekonomi->id,
                'name' => 'Hino Dutro',
                'vehicle_type' => 'Minibus',
                'plate_number' => 'B 7890 STU',
                'capacity' => 25,
                'description' => 'Minibus ekonomi kapasitas besar',
                'is_available' => true,
            ],
        ];

        foreach ($armadas as $armada) {
            Armada::create($armada);
        }
    }
}
