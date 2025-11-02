<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverLocationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('driver_locations')->truncate();

        $drivers = DB::table('users')->where('role', 'driver')->pluck('id')->toArray();
        $workSessions = DB::table('work_sessions')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        foreach (range(1, 12) as $i) {
            DB::table('driver_locations')->insert([
                'user_id' => $faker->randomElement($drivers) ?? null,
                'work_session_id' => $faker->optional()->randomElement($workSessions) ?? null,
                'latitude' => $faker->latitude( -8.7, -6.1 ),
                'longitude' => $faker->longitude(110, 115),
                'recorded_at' => now()->subMinutes(rand(0, 120)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}