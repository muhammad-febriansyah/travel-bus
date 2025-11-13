<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Kode booking unik
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('armada_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->date('travel_date'); // Tanggal keberangkatan
            $table->time('travel_time')->nullable(); // Jam keberangkatan
            $table->integer('total_passengers'); // Jumlah penumpang
            $table->decimal('price_per_person', 10, 2); // Harga per orang
            $table->decimal('total_price', 10, 2); // Total harga
            $table->string('pickup_location')->nullable(); // Lokasi penjemputan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('whatsapp_url')->nullable(); // Link WA admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
