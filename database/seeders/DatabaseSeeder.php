<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepala@example.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
        ]);

        User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@example.com',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
        ]);
    }
}
