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
        Schema::table('armadas', function (Blueprint $table) {
            $table->foreignId('seat_layout_id')
                ->nullable()
                ->after('category_id')
                ->constrained('seat_layouts')
                ->nullOnDelete()
                ->comment('Seat layout configuration reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('armadas', function (Blueprint $table) {
            $table->dropForeign(['seat_layout_id']);
            $table->dropColumn('seat_layout_id');
        });
    }
};
