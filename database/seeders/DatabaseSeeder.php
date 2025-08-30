<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // ganti di production
            'phone' => '081234567890',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Staff
        User::create([
            'name' => 'Staff Transport',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'role' => 'staff',
            'is_active' => true,
        ]);

        // Driver
        User::create([
            'name' => 'Driver One',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'role' => 'driver',
            'is_active' => true,
        ]);

        // Guide
        User::create([
            'name' => 'Guide One',
            'email' => 'guide@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567893',
            'role' => 'guide',
            'is_active' => true,
        ]);
    }
}
