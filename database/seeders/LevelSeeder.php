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
        $worldAngka = \App\Models\World::where('slug', 'dunia-angka')->first();
        if ($worldAngka) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Level::create([
                    'world_id' => $worldAngka->id,
                    'title' => 'Level '.$i.': Penjumlahan Dasar',
                    'sequence' => $i,
                    'points_reward' => 100 + ($i * 10),
                ]);
            }
        }

        $worldPecahan = \App\Models\World::where('slug', 'dunia-pecahan')->first();
        if ($worldPecahan) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Level::create([
                    'world_id' => $worldPecahan->id,
                    'title' => 'Level '.$i.': Konsep Pecahan',
                    'sequence' => $i,
                    'points_reward' => 120 + ($i * 10),
                ]);
            }
        }

        $worldTantangan = \App\Models\World::where('slug', 'dunia-tantangan')->first();
        if ($worldTantangan) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Level::create([
                    'world_id' => $worldTantangan->id,
                    'title' => 'Level '.$i.': Perkalian & Pembagian',
                    'sequence' => $i,
                    'points_reward' => 150 + ($i * 10),
                ]);
            }
        }
    }
}
