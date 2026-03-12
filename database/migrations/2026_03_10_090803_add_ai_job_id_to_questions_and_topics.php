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
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('ai_job_id')->nullable()->after('chapter_id');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->unsignedBigInteger('ai_job_id')->nullable()->after('chapter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('ai_job_id');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('ai_job_id');
        });
    }
};
