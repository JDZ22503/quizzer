<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Class 9
            ['name' => 'Mathematics',        'class' => '9'],
            ['name' => 'Science',            'class' => '9'],
            ['name' => 'English',            'class' => '9'],
            ['name' => 'Social Studies',     'class' => '9'],
            ['name' => 'Hindi',              'class' => '9'],

            // Class 10
            ['name' => 'Mathematics',        'class' => '10'],
            ['name' => 'Physics',            'class' => '10'],
            ['name' => 'Chemistry',          'class' => '10'],
            ['name' => 'Biology',            'class' => '10'],
            ['name' => 'English',            'class' => '10'],
            ['name' => 'Social Science',     'class' => '10'],
            ['name' => 'Hindi',              'class' => '10'],

            // Class 11
            ['name' => 'Mathematics',        'class' => '11'],
            ['name' => 'Physics',            'class' => '11'],
            ['name' => 'Chemistry',          'class' => '11'],
            ['name' => 'Biology',            'class' => '11'],
            ['name' => 'Computer Science',   'class' => '11'],
            ['name' => 'Economics',          'class' => '11'],
            ['name' => 'Accountancy',        'class' => '11'],

            // Class 12
            ['name' => 'Mathematics',        'class' => '12'],
            ['name' => 'Physics',            'class' => '12'],
            ['name' => 'Chemistry',          'class' => '12'],
            ['name' => 'Biology',            'class' => '12'],
            ['name' => 'Computer Science',   'class' => '12'],
            ['name' => 'Economics',          'class' => '12'],
            ['name' => 'Accountancy',        'class' => '12'],
            ['name' => 'Gujarati',           'class' => '9'],
            ['name' => 'Gujarati',           'class' => '10'],
            ['name' => 'Gujarati',           'class' => '11'],
            ['name' => 'Gujarati',           'class' => '12'],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insertOrIgnore(array_merge($subject, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
