<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assignments')->truncate();

        $orders = DB::table('orders')->pluck('id')->toArray();
        $drivers = DB::table('users')->where('role', 'driver')->pluck('id')->toArray();
        $guides = DB::table('users')->where('role', 'guide')->pluck('id')->toArray();
        $staffs = DB::table('users')->where('role', 'staff')->pluck('id')->toArray();
        $vehicles = DB::table('vehicles')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        foreach ($orders as $orderId) {
            DB::table('assignments')->insert([
                'order_id' => $orderId,
                'staff_id' => $faker->randomElement($staffs) ?? null,
                'driver_id' => $faker->randomElement($drivers) ?? null,
                'guide_id' => $faker->optional()->randomElement($guides) ?? null,
                'vehicle_id' => $faker->optional()->randomElement($vehicles) ?? null,
                'scheduled_start' => now()->addDays(rand(0, 5)),
                'scheduled_end' => now()->addDays(rand(1, 6)),
                'estimated_hours' => rand(1, 12),
                'status' => 'assigned',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}