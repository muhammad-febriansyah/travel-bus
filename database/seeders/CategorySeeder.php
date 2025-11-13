<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Eksekutif',
                'slug' => Str::slug('Eksekutif'),
            ],
            [
                'name' => 'Ekonomi',
                'slug' => Str::slug('Ekonomi'),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
