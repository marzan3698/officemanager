<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Shantikotha Office');
            $table->string('short_name')->default('Office');
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('theme_color')->default('#9D1C5B');
            $table->timestamps();
        });

        // Insert default row
        DB::table('general_settings')->insert([
            'site_name'   => 'Shantikotha Office',
            'short_name'  => 'Office',
            'theme_color' => '#9D1C5B',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
