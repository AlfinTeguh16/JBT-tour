<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkSessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('work_sessions')->truncate();

        $users = DB::table('users')->whereIn('role', ['driver', 'guide'])->pluck('id')->toArray();
        $assignments = DB::table('assignments')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        foreach (range(1, 8) as $i) {
            $userId = $faker->randomElement($users);
            $assignmentId = $faker->optional()->randomElement($assignments) ?? null;
            $started = now()->subHours(rand(1, 48));
            $ended = (rand(0, 1) ? $started->copy()->addHours(rand(1, 10)) : null);

            DB::table('work_sessions')->insert([
                'user_id' => $userId,
                'assignment_id' => $assignmentId,
                'started_at' => $started,
                'ended_at' => $ended,
                'hours_decimal' => $ended ? round($ended->diffInMinutes($started) / 60, 2) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}