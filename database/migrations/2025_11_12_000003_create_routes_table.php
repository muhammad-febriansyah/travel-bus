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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin'); // Kota asal
            $table->string('destination'); // Kota tujuan
            $table->string('route_code')->unique(); // Kode rute (misal: JKT-BDG)
            $table->decimal('distance', 8, 2)->nullable(); // Jarak dalam KM
            $table->integer('estimated_duration')->nullable(); // Estimasi waktu (menit)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
