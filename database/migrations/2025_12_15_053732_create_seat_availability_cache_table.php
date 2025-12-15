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
        Schema::create('seat_availability_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armada_id')->constrained()->onDelete('cascade')->comment('Armada reference');
            $table->date('travel_date')->comment('Travel date for availability check');
            $table->time('travel_time')->nullable()->comment('Travel time for availability check');
            $table->json('occupied_seats')->comment('Array of occupied seat numbers');
            $table->integer('available_count')->default(0)->comment('Number of available seats');
            $table->integer('total_capacity')->comment('Total armada capacity');
            $table->timestamp('last_updated')->useCurrent()->comment('Cache last updated timestamp');
            $table->timestamps();

            // Unique index for cache lookup
            $table->unique(['armada_id', 'travel_date', 'travel_time'], 'unique_armada_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_availability_cache');
    }
};
