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
        Schema::table('students', function (Blueprint $table) {
            $table->integer('xp')->default(0)->after('roll_number');
            $table->integer('streak')->default(0)->after('xp');
            $table->integer('level')->default(1)->after('streak');
            $table->timestamp('last_active_at')->nullable()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['xp', 'streak', 'level', 'last_active_at']);
        });
    }
};
