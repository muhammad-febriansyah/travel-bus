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
        Schema::create('seat_layouts', function (Blueprint $table) {
            $table->id();
            $table->integer('capacity')->unique()->comment('Total passenger capacity (12, 20, 45, etc.)');
            $table->integer('rows')->comment('Number of seat rows');
            $table->integer('columns')->comment('Seats per row');
            $table->enum('layout_type', ['bus', 'minibus', 'van'])->comment('Vehicle layout type');
            $table->json('seat_map_config')->comment('Seat grid configuration including aisles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_layouts');
    }
};
