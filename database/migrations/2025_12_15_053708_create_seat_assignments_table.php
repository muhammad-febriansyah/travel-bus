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
        Schema::create('seat_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->comment('Booking reference');
            $table->string('seat_number', 10)->comment('Seat number (e.g., A1, B2)');
            $table->string('passenger_name')->nullable()->comment('Optional passenger name for this seat');
            $table->enum('status', ['reserved', 'confirmed', 'cancelled'])->default('reserved')->comment('Seat assignment status');
            $table->timestamps();

            // Indexes
            $table->unique(['booking_id', 'seat_number'], 'unique_booking_seat');
            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_assignments');
    }
};
