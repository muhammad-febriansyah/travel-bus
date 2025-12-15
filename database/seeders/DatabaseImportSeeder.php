<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('Starting database import...');

        // Import Categories
        $this->importCategories();

        // Import/Update Settings
        $this->importSettings();

        // Import Customers
        $this->importCustomers();

        // Import Routes
        $this->importRoutes();

        // Import Armadas
        $this->importArmadas();

        // Import Users
        $this->importUsers();

        // Import Bookings
        $this->importBookings();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Database import completed successfully!');
        $this->assignSeatLayouts();
    }

    protected function importCategories(): void
    {
        $this->command->info('Importing categories...');

        // Clear existing categories
        DB::table('categories')->whereIn('id', [1, 2])->delete();

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Ekonomi', 'slug' => 'ekonomi', 'created_at' => '2024-11-20 03:09:16', 'updated_at' => '2024-11-20 03:09:16'],
            ['id' => 2, 'name' => 'Eksekutif', 'slug' => 'eksekutif', 'created_at' => '2024-11-20 03:09:23', 'updated_at' => '2024-11-20 03:09:23'],
        ]);

        $this->command->info('✓ Categories imported');
    }

    protected function importSettings(): void
    {
        $this->command->info('Updating settings...');

        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => 'Travel Booking System',
                'email' => 'travelbooking@example.com',
                'phone' => '08123456789',
                'address' => 'Jl. Muara Bungo - Jambi',
                'instagram_url' => 'https://instagram.com/travelbooking',
                'facebook_url' => 'https://facebook.com/travelbooking',
                'whatsapp_number' => '08123456789',
                'keyword' => 'travel, booking, muara bungo, jambi',
                'description' => 'Sistem booking travel Muara Bungo - Jambi',
                'updated_at' => now(),
            ]
        );

        $this->command->info('✓ Settings updated');
    }

    protected function importCustomers(): void
    {
        $this->command->info('Importing customers...');

        // Clear existing customers
        DB::table('customers')->whereIn('id', range(1, 26))->delete();

        $customers = [
            ['id' => 1, 'name' => 'afwanza', 'phone' => '085742942916', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 01:58:04', 'updated_at' => '2024-11-22 01:58:04'],
            ['id' => 2, 'name' => 'RAMLI', 'phone' => '085763299936', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 02:02:33', 'updated_at' => '2024-11-22 02:02:33'],
            ['id' => 3, 'name' => 'YANTI SARI', 'phone' => '081267883339', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 02:04:14', 'updated_at' => '2024-11-22 02:04:14'],
            ['id' => 4, 'name' => 'FITRI', 'phone' => '082178959030', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 02:05:39', 'updated_at' => '2024-11-22 02:05:39'],
            ['id' => 5, 'name' => 'DIAN', 'phone' => '085742943355', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 02:07:02', 'updated_at' => '2024-11-22 02:07:02'],
            ['id' => 6, 'name' => 'ariana', 'phone' => '082371856001', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 07:57:10', 'updated_at' => '2024-11-22 07:57:10'],
            ['id' => 7, 'name' => 'JEMI', 'phone' => '082284849994', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 07:59:42', 'updated_at' => '2024-11-22 07:59:42'],
            ['id' => 8, 'name' => 'YOHANA', 'phone' => '082283775334', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 08:03:10', 'updated_at' => '2024-11-22 08:03:10'],
            ['id' => 9, 'name' => 'yulia', 'phone' => '085267311169', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 08:05:42', 'updated_at' => '2024-11-22 08:05:42'],
            ['id' => 10, 'name' => 'NURHADIAH', 'phone' => '085211151170', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-22 08:09:05', 'updated_at' => '2024-11-22 08:09:05'],
            ['id' => 11, 'name' => 'Meri', 'phone' => '085263123352', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-23 00:55:54', 'updated_at' => '2024-11-23 00:55:54'],
            ['id' => 12, 'name' => 'ricky', 'phone' => '082283932939', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:29:12', 'updated_at' => '2024-11-24 01:29:12'],
            ['id' => 13, 'name' => 'Ahmad', 'phone' => '085270757990', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:31:20', 'updated_at' => '2024-11-24 01:31:20'],
            ['id' => 14, 'name' => 'novi', 'phone' => '082283750039', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:32:49', 'updated_at' => '2024-11-24 01:32:49'],
            ['id' => 15, 'name' => 'helni', 'phone' => '082283933009', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:33:58', 'updated_at' => '2024-11-24 01:33:58'],
            ['id' => 16, 'name' => 'Yopi', 'phone' => '082284842001', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:35:12', 'updated_at' => '2024-11-24 01:35:12'],
            ['id' => 17, 'name' => 'siti', 'phone' => '082272888829', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 01:36:24', 'updated_at' => '2024-11-24 01:36:24'],
            ['id' => 18, 'name' => 'ade supnadi', 'phone' => '08127699997', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 04:13:53', 'updated_at' => '2024-11-24 04:13:53'],
            ['id' => 19, 'name' => 'Rahman', 'phone' => '085263121121', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:11:05', 'updated_at' => '2024-11-24 07:11:05'],
            ['id' => 20, 'name' => 'heri', 'phone' => '085382799998', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:13:05', 'updated_at' => '2024-11-24 07:13:05'],
            ['id' => 21, 'name' => 'Alfi', 'phone' => '082283937771', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:14:53', 'updated_at' => '2024-11-24 07:14:53'],
            ['id' => 22, 'name' => 'Bachtiar', 'phone' => '085263122226', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:16:32', 'updated_at' => '2024-11-24 07:16:32'],
            ['id' => 23, 'name' => 'Usman', 'phone' => '085263455555', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:17:48', 'updated_at' => '2024-11-24 07:17:48'],
            ['id' => 24, 'name' => 'Helni', 'phone' => '081267222224', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-24 07:19:01', 'updated_at' => '2024-11-24 07:19:01'],
            ['id' => 25, 'name' => 'rozi', 'phone' => '085270721113', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-25 01:40:17', 'updated_at' => '2024-11-25 01:40:17'],
            ['id' => 26, 'name' => 'winda', 'phone' => '082371766612', 'email' => null, 'address' => null, 'id_card_number' => null, 'created_at' => '2024-11-26 03:44:54', 'updated_at' => '2024-11-26 03:44:54'],
        ];

        DB::table('customers')->insert($customers);

        $this->command->info('✓ ' . count($customers) . ' customers imported');
    }

    protected function importRoutes(): void
    {
        $this->command->info('Importing routes...');

        // Clear existing routes
        DB::table('routes')->whereIn('id', range(1, 6))->delete();

        // Note: old schema had 'price' but new schema doesn't. Prices are now in bookings table as price_per_person
        // estimated_duration is in minutes: 3 jam = 180, 2.5 jam = 150, 2 jam = 120
        $routes = [
            ['id' => 1, 'origin' => 'MUARA BUNGO', 'destination' => 'JAMBI', 'route_code' => 'MB-JMB', 'distance' => 150, 'estimated_duration' => 180, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:04:47', 'updated_at' => '2024-11-20 03:04:47'],
            ['id' => 2, 'origin' => 'JAMBI', 'destination' => 'MUARA BUNGO', 'route_code' => 'JMB-MB', 'distance' => 150, 'estimated_duration' => 180, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:05:17', 'updated_at' => '2024-11-20 03:05:17'],
            ['id' => 3, 'origin' => 'RIMBO ALAM', 'destination' => 'JAMBI', 'route_code' => 'RA-JMB', 'distance' => 130, 'estimated_duration' => 150, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:06:02', 'updated_at' => '2024-11-20 03:06:02'],
            ['id' => 4, 'origin' => 'JAMBI', 'destination' => 'RIMBO ALAM', 'route_code' => 'JMB-RA', 'distance' => 130, 'estimated_duration' => 150, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:06:31', 'updated_at' => '2024-11-20 03:06:31'],
            ['id' => 5, 'origin' => 'PELEPAT', 'destination' => 'JAMBI', 'route_code' => 'PLP-JMB', 'distance' => 100, 'estimated_duration' => 120, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:07:05', 'updated_at' => '2024-11-20 03:07:05'],
            ['id' => 6, 'origin' => 'JAMBI', 'destination' => 'PELEPAT', 'route_code' => 'JMB-PLP', 'distance' => 100, 'estimated_duration' => 120, 'description' => null, 'is_active' => true, 'created_at' => '2024-11-20 03:07:31', 'updated_at' => '2024-11-20 03:07:31'],
        ];

        DB::table('routes')->insert($routes);

        $this->command->info('✓ ' . count($routes) . ' routes imported');
    }

    protected function importArmadas(): void
    {
        $this->command->info('Importing armadas...');

        // Clear existing armadas
        DB::table('armadas')->whereIn('id', range(1, 10))->delete();

        $armadas = [
            ['id' => 1, 'name' => 'BH 7894 MC', 'plate_number' => 'BH 7894 MC', 'capacity' => 6, 'category_id' => 2, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:10:17', 'updated_at' => '2024-11-20 03:10:17'],
            ['id' => 2, 'name' => 'BH 7890 MC', 'plate_number' => 'BH 7890 MC', 'capacity' => 6, 'category_id' => 2, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:10:48', 'updated_at' => '2024-11-20 03:10:48'],
            ['id' => 3, 'name' => 'BH 7892 MC', 'plate_number' => 'BH 7892 MC', 'capacity' => 6, 'category_id' => 2, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:11:08', 'updated_at' => '2024-11-20 03:11:08'],
            ['id' => 4, 'name' => 'BH 7444 MC', 'plate_number' => 'BH 7444 MC', 'capacity' => 6, 'category_id' => 2, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:11:26', 'updated_at' => '2024-11-20 03:11:26'],
            ['id' => 5, 'name' => 'BH 7895 MC', 'plate_number' => 'BH 7895 MC', 'capacity' => 6, 'category_id' => 2, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:11:43', 'updated_at' => '2024-11-20 03:11:43'],
            ['id' => 6, 'name' => 'BH 7008 MC', 'plate_number' => 'BH 7008 MC', 'capacity' => 6, 'category_id' => 1, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:12:27', 'updated_at' => '2024-11-20 03:12:27'],
            ['id' => 7, 'name' => 'BH 7009 MC', 'plate_number' => 'BH 7009 MC', 'capacity' => 6, 'category_id' => 1, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:13:03', 'updated_at' => '2024-11-20 03:13:03'],
            ['id' => 8, 'name' => 'BH 7010 MC', 'plate_number' => 'BH 7010 MC', 'capacity' => 6, 'category_id' => 1, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:13:36', 'updated_at' => '2024-11-20 03:13:36'],
            ['id' => 9, 'name' => 'BH 7012 MC', 'plate_number' => 'BH 7012 MC', 'capacity' => 6, 'category_id' => 1, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:13:53', 'updated_at' => '2024-11-20 03:13:53'],
            ['id' => 10, 'name' => 'BH 7898 MC', 'plate_number' => 'BH 7898 MC', 'capacity' => 6, 'category_id' => 1, 'seat_layout_id' => null, 'vehicle_type' => 'van', 'description' => null, 'image' => null, 'is_available' => true, 'created_at' => '2024-11-20 03:14:11', 'updated_at' => '2024-11-20 03:14:11'],
        ];

        DB::table('armadas')->insert($armadas);

        $this->command->info('✓ ' . count($armadas) . ' armadas imported');
    }

    protected function importUsers(): void
    {
        $this->command->info('Importing users...');

        // Clear existing users
        DB::table('users')->whereIn('id', [1, 2])->delete();

        $users = [
            ['id' => 1, 'name' => 'admin', 'email' => 'admin@admin.com', 'email_verified_at' => null, 'password' => '$2y$12$Y3gPP1kgOxE/m8qHxkbBp.sWzONSk3Ag93cAQ13OKqvfE44jXE4c.', 'remember_token' => null, 'created_at' => '2024-11-20 02:59:26', 'updated_at' => '2024-11-20 02:59:26'],
            ['id' => 2, 'name' => 'operator', 'email' => 'operator@admin.com', 'email_verified_at' => null, 'password' => '$2y$12$TXiPPYLi1AXfZV5N.sH3WuP4.8oqLuoSxGxSe5YC4qpOmD3sOmfEK', 'remember_token' => null, 'created_at' => '2024-11-20 03:00:13', 'updated_at' => '2024-11-20 03:00:13'],
        ];

        DB::table('users')->insert($users);

        $this->command->info('✓ ' . count($users) . ' users imported');
        $this->command->warn('User passwords from backup (admin@admin.com and operator@admin.com)');
    }

    protected function importBookings(): void
    {
        $this->command->info('Importing bookings...');

        // Clear existing bookings
        DB::table('bookings')->whereIn('id', range(1, 6))->delete();

        // Note: old schema had dropoff_location, new schema doesn't. Using pickup_location and notes instead.
        $bookings = [
            ['id' => 1, 'booking_code' => 'BK-67409D5C5A5E1', 'customer_id' => 1, 'route_id' => 1, 'armada_id' => 1, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 01:58:04', 'updated_at' => '2024-11-22 02:00:44'],
            ['id' => 2, 'booking_code' => 'BK-67409E211BEF2', 'customer_id' => 2, 'route_id' => 1, 'armada_id' => 1, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 02:02:33', 'updated_at' => '2024-11-22 02:03:13'],
            ['id' => 3, 'booking_code' => 'BK-67409E86AF1A2', 'customer_id' => 3, 'route_id' => 1, 'armada_id' => 1, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 02:04:14', 'updated_at' => '2024-11-22 02:04:54'],
            ['id' => 4, 'booking_code' => 'BK-67409EEB92AF6', 'customer_id' => 4, 'route_id' => 1, 'armada_id' => 1, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 02:05:39', 'updated_at' => '2024-11-22 02:06:19'],
            ['id' => 5, 'booking_code' => 'BK-67409F4E57FF3', 'customer_id' => 5, 'route_id' => 1, 'armada_id' => 1, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 02:07:02', 'updated_at' => '2024-11-22 02:07:42'],
            ['id' => 6, 'booking_code' => 'BK-6740E18E3AEBB', 'customer_id' => 6, 'route_id' => 1, 'armada_id' => 2, 'category_id' => 2, 'travel_date' => '2024-11-23', 'travel_time' => '03:00:00', 'pickup_location' => 'Terminal Bungo', 'total_passengers' => 1, 'price_per_person' => 130000, 'total_price' => 130000, 'notes' => 'Dropoff: Terminal Jambi', 'whatsapp_url' => null, 'status' => 'confirmed', 'created_at' => '2024-11-22 07:57:10', 'updated_at' => '2024-11-22 07:57:50'],
        ];

        DB::table('bookings')->insert($bookings);

        $this->command->info('✓ ' . count($bookings) . ' bookings imported');
    }

    protected function assignSeatLayouts(): void
    {
        $this->command->info('Assigning seat layouts to armadas...');

        // Get the 6-passenger layout ID
        $layoutId = DB::table('seat_layouts')->where('capacity', 6)->value('id');

        if ($layoutId) {
            // Assign to all armadas with capacity 6
            DB::table('armadas')
                ->where('capacity', 6)
                ->update(['seat_layout_id' => $layoutId]);

            $count = DB::table('armadas')->where('capacity', 6)->count();
            $this->command->info("✓ Assigned 6-passenger layout to {$count} armadas");
        } else {
            $this->command->warn('! 6-passenger layout not found');
        }
    }
}
