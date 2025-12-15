<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SeatLayout;

class SeatLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 6 Passenger Small Van Layout (2 configuration - no aisle)
        SeatLayout::updateOrCreate(
            ['capacity' => 6],
            [
                'rows' => 3,
                'columns' => 2,
                'layout_type' => 'van',
                'seat_map_config' => [
                    'rows' => 3,
                    'columns' => 2,
                    'aisle_positions' => [],
                    'grid' => [
                        ['row' => 1, 'seats' => ['A1', 'A2']],
                        ['row' => 2, 'seats' => ['B1', 'B2']],
                        ['row' => 3, 'seats' => ['C1', 'C2']],
                    ],
                ],
            ]
        );

        // 12 Passenger Van Layout (2-1 configuration)
        SeatLayout::updateOrCreate(
            ['capacity' => 12],
            [
                'rows' => 4,
                'columns' => 3,
                'layout_type' => 'van',
                'seat_map_config' => [
                    'rows' => 4,
                    'columns' => 3,
                    'aisle_positions' => [2],
                    'grid' => [
                        ['row' => 1, 'seats' => ['A1', 'A2', null, 'A3']],
                        ['row' => 2, 'seats' => ['B1', 'B2', null, 'B3']],
                        ['row' => 3, 'seats' => ['C1', 'C2', null, 'C3']],
                        ['row' => 4, 'seats' => ['D1', 'D2', null, 'D3']],
                    ],
                ],
            ]
        );

        // 20 Passenger Medium Bus Layout (2-2 configuration)
        SeatLayout::updateOrCreate(
            ['capacity' => 20],
            [
                'rows' => 5,
                'columns' => 4,
                'layout_type' => 'minibus',
                'seat_map_config' => [
                    'rows' => 5,
                    'columns' => 4,
                    'aisle_positions' => [2],
                    'grid' => [
                        ['row' => 1, 'seats' => ['A1', 'A2', null, 'A3', 'A4']],
                        ['row' => 2, 'seats' => ['B1', 'B2', null, 'B3', 'B4']],
                        ['row' => 3, 'seats' => ['C1', 'C2', null, 'C3', 'C4']],
                        ['row' => 4, 'seats' => ['D1', 'D2', null, 'D3', 'D4']],
                        ['row' => 5, 'seats' => ['E1', 'E2', null, 'E3', 'E4']],
                    ],
                ],
            ]
        );

        // 45 Passenger Large Bus Layout (2-3 configuration)
        $largeGridRows = [];
        $rowLetters = range('A', 'J'); // A through J = 10 rows but we need 9 for 45 seats

        for ($i = 0; $i < 9; $i++) {
            $largeGridRows[] = [
                'row' => $i + 1,
                'seats' => [
                    $rowLetters[$i] . '1',
                    $rowLetters[$i] . '2',
                    null, // Aisle
                    $rowLetters[$i] . '3',
                    $rowLetters[$i] . '4',
                    $rowLetters[$i] . '5',
                ],
            ];
        }

        SeatLayout::updateOrCreate(
            ['capacity' => 45],
            [
                'rows' => 9,
                'columns' => 5,
                'layout_type' => 'bus',
                'seat_map_config' => [
                    'rows' => 9,
                    'columns' => 5,
                    'aisle_positions' => [2],
                    'grid' => $largeGridRows,
                ],
            ]
        );

        $this->command->info('Seat layouts seeded successfully!');
        $this->command->info('Created layouts for: 6, 12, 20, and 45 passengers');
    }
}
