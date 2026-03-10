<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
        ]);

        \App\Models\Teacher::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Default Teacher',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'phone' => '1234567890',
                'subject_specialization' => 'All',
            ]
        );
    }
}
