<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->truncate();

        $users = DB::table('users')->pluck('id')->toArray();
        $assignments = DB::table('assignments')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        foreach (range(1, 10) as $i) {
            DB::table('notifications')->insert([
                'user_id' => $faker->randomElement($users),
                'title' => ucfirst($faker->words(3, true)),
                'body' => $faker->sentence,
                'is_read' => $faker->boolean(30),
                'assignment_id' => $faker->optional()->randomElement($assignments) ?? null,
                'status' => $faker->randomElement(['pending','approved','declined']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}