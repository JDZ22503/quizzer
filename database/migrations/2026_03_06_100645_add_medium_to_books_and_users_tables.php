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
        Schema::table('books', function (Blueprint $table) {
            $table->string('medium')->default('english')->after('question_preference');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('medium')->default('english')->after('roll_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books_and_users_tables', function (Blueprint $table) {
            //
        });
    }
};
