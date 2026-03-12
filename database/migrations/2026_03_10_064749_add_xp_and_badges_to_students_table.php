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
            if (! Schema::hasColumn('students', 'xp_total')) {
                $table->integer('xp_total')->default(0);
            }
            if (! Schema::hasColumn('students', 'level')) {
                $table->integer('level')->default(1);
            }
            if (! Schema::hasColumn('students', 'badges')) {
                $table->json('badges')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['xp_total', 'level', 'badges']);
        });
    }
};
