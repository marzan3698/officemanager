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
        Schema::table('salary_logs', function (Blueprint $table) {
            $table->string('payment_ref')->nullable()->after('paid_at');
            $table->string('proof_file')->nullable()->after('payment_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_logs', function (Blueprint $table) {
            //
        });
    }
};
