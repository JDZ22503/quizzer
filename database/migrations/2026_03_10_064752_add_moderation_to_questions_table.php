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
            if (!Schema::hasColumn('questions', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            }
            if (!Schema::hasColumn('questions', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable();
            }
            if (!Schema::hasColumn('questions', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['status', 'moderated_by', 'moderated_at']);
        });
    }
};
