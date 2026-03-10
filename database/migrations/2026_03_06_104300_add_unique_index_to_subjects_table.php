<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Find and Clean Duplicates
        $duplicates = DB::table('subjects')
            ->select('name', 'class', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->groupBy('name', 'class')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            // Get all IDs for this subject name and class except the one to keep
            $idsToDelete = DB::table('subjects')
                ->where('name', $duplicate->name)
                ->where('class', $duplicate->class)
                ->where('id', '<>', $duplicate->keep_id)
                ->pluck('id');

            // Re-associate books to the kept ID
            DB::table('books')
                ->whereIn('subject_id', $idsToDelete)
                ->update(['subject_id' => $duplicate->keep_id]);

            // Delete the duplicate subjects
            DB::table('subjects')->whereIn('id', $idsToDelete)->delete();
        }

        // 2. Add Unique Index
        Schema::table('subjects', function (Blueprint $table) {
            $table->unique(['name', 'class']);
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropUnique(['name', 'class']);
        });
    }
};
