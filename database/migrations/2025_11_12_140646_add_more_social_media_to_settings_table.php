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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('twitter_url');
            $table->string('youtube_url')->nullable()->after('whatsapp_number');
            $table->string('tiktok_url')->nullable()->after('youtube_url');
            $table->string('linkedin_url')->nullable()->after('tiktok_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_number',
                'youtube_url',
                'tiktok_url',
                'linkedin_url',
            ]);
        });
    }
};
