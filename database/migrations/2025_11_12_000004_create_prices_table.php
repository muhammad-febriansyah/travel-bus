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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2); // Harga
            $table->decimal('discount', 10, 2)->default(0); // Diskon (jika ada)
            $table->date('valid_from')->nullable(); // Berlaku dari
            $table->date('valid_until')->nullable(); // Berlaku sampai
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint untuk route + category
            $table->unique(['route_id', 'category_id', 'valid_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
