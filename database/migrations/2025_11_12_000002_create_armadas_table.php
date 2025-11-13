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
        Schema::create('armadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama armada
            $table->string('vehicle_type'); // Bus, Minibus, Travel, dll
            $table->string('plate_number')->unique(); // Nomor plat
            $table->integer('capacity'); // Kapasitas penumpang
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Path foto armada
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armadas');
    }
};
