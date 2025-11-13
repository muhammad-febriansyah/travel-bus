<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Price;
use App\Models\Route;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eksekutif = Category::where('slug', 'eksekutif')->first();
        $ekonomi = Category::where('slug', 'ekonomi')->first();
        $routes = Route::all();

        foreach ($routes as $route) {
            // Harga berdasarkan jarak
            $basePrice = $route->distance * 1500; // Rp 1.500 per KM untuk ekonomi

            // Harga Ekonomi
            Price::create([
                'route_id' => $route->id,
                'category_id' => $ekonomi->id,
                'price' => $basePrice,
                'discount' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
            ]);

            // Harga Eksekutif (1.8x lebih mahal dari ekonomi)
            Price::create([
                'route_id' => $route->id,
                'category_id' => $eksekutif->id,
                'price' => $basePrice * 1.8,
                'discount' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
            ]);
        }
    }
}
