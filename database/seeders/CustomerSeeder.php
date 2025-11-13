<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'id_card_number' => '3171011234567890',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'phone' => '082345678901',
                'address' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
                'id_card_number' => '3174022345678901',
            ],
            [
                'name' => 'Ahmad Dhani',
                'email' => 'ahmad.dhani@email.com',
                'phone' => '083456789012',
                'address' => 'Jl. Thamrin No. 89, Jakarta Pusat',
                'id_card_number' => '3171033456789012',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'phone' => '084567890123',
                'address' => 'Jl. Dago No. 56, Bandung',
                'id_card_number' => '3273044567890123',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@email.com',
                'phone' => '085678901234',
                'address' => 'Jl. Malioboro No. 23, Yogyakarta',
                'id_card_number' => '3471055678901234',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '086789012345',
                'address' => 'Jl. Pemuda No. 78, Semarang',
                'id_card_number' => '3374066789012345',
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@email.com',
                'phone' => '087890123456',
                'address' => 'Jl. Basuki Rahmat No. 34, Surabaya',
                'id_card_number' => '3578077890123456',
            ],
            [
                'name' => 'Rina Wulandari',
                'email' => 'rina.wulandari@email.com',
                'phone' => '088901234567',
                'address' => 'Jl. Asia Afrika No. 90, Bandung',
                'id_card_number' => '3273088901234567',
            ],
            [
                'name' => 'Bambang Pamungkas',
                'email' => 'bambang.pamungkas@email.com',
                'phone' => '089012345678',
                'address' => 'Jl. HR Rasuna Said No. 67, Jakarta Selatan',
                'id_card_number' => '3174099012345678',
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@email.com',
                'phone' => '081123456789',
                'address' => 'Jl. Diponegoro No. 45, Solo',
                'id_card_number' => '3372101123456789',
            ],
            [
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@email.com',
                'phone' => '082234567890',
                'address' => 'Jl. Ijen No. 12, Malang',
                'id_card_number' => '3573112234567890',
            ],
            [
                'name' => 'Linda Wijayanti',
                'email' => 'linda.wijayanti@email.com',
                'phone' => '083345678901',
                'address' => 'Jl. Pahlawan No. 56, Surabaya',
                'id_card_number' => '3578123345678901',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
