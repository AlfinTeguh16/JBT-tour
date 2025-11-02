<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Non-aktifkan foreign key constraints sementara supaya truncate tidak error
        Schema::disableForeignKeyConstraints();

        // Gunakan truncate (lebih cepat) sekarang foreign key checks non-aktif
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        // Insert fixed users
        DB::table('users')->insert([
            [
                'name' => 'Admin Master',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Transport',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'role' => 'staff',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Driver One',
                'email' => 'driver@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'role' => 'driver',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guide One',
                'email' => 'guide@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'role' => 'guide',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Additional random users
        $faker = \Faker\Factory::create();
        foreach (range(1, 6) as $i) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'role' => $faker->randomElement(['staff','driver','guide']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
