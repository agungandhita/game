<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $worlds = \App\Models\World::all();

        foreach ($worlds as $world) {
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\Level::firstOrCreate([
                    'world_id' => $world->id,
                    'sequence' => $i,
                ], [
                    'title' => 'Level ' . $i . ': Tantangan ' . $world->name,
                    'points_reward' => 100 + ($i * 10 * $world->class),
                ]);
            }
        }
    }
}
