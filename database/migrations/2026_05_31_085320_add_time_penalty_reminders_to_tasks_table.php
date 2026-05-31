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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('due_date')->change();
            $table->decimal('penalty_amount', 10, 2)->default(0)->after('status');
            $table->boolean('is_penalized')->default(false)->after('penalty_amount');
            $table->integer('reminder_count')->default(0)->after('is_penalized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->date('due_date')->change();
            $table->dropColumn(['penalty_amount', 'is_penalized', 'reminder_count']);
        });
    }
};
