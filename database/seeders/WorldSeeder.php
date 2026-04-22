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
                'name' => 'Dunia Pemula',
                'class' => 1,
                'slug' => 'dunia-pemula-1',
                'description' => 'Awal mula petualangan belajarmu!',
            ],
            [
                'name' => 'Dunia Penerus',
                'class' => 2,
                'slug' => 'dunia-penerus-2',
                'description' => 'Mulai mengerti hal baru.',
            ],
            [
                'name' => 'Dunia Angka',
                'class' => 3,
                'slug' => 'dunia-angka-3',
                'description' => 'Belajar penjumlahan dan pengurangan dengan seru!',
            ],
            [
                'name' => 'Dunia Menengah',
                'class' => 4,
                'slug' => 'dunia-menengah-4',
                'description' => 'Menjelajahi pecahan dan desimal.',
            ],
            [
                'name' => 'Dunia Analisa',
                'class' => 5,
                'slug' => 'dunia-analisa-5',
                'description' => 'Taklukkan perkalian, pembagian, dan soal cerita.',
            ],
            [
                'name' => 'Dunia Master',
                'class' => 6,
                'slug' => 'dunia-master-6',
                'description' => 'Kamu sudah hampir lulus dari semua ujian heroik.',
            ],
        ];

        foreach ($worlds as $world) {
            \App\Models\World::firstOrCreate(['slug' => $world['slug']], $world);
        }
    }
}
