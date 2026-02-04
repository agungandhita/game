<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $worlds = [
            [
                'name' => 'Dunia Angka',
                'class' => 3,
                'slug' => 'dunia-angka',
                'description' => 'Belajar penjumlahan dan pengurangan dengan seru!',
            ],
            [
                'name' => 'Dunia Pecahan',
                'class' => 4,
                'slug' => 'dunia-pecahan',
                'description' => 'Menjelajahi pecahan dan desimal.',
            ],
            [
                'name' => 'Dunia Tantangan',
                'class' => 5,
                'slug' => 'dunia-tantangan',
                'description' => 'Taklukkan perkalian, pembagian, dan soal cerita.',
            ],
        ];

        foreach ($worlds as $world) {
            \App\Models\World::create($world);
        }
    }
}
