<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Jago Penjumlahan',
                'description' => 'Menyelesaikan semua level penjumlahan dengan sempurna.',
                'icon_path' => 'badges/addition_master.png',
                'type' => 'skill',
                'requirement_type' => 'levels_completed',
                'requirement_value' => 5, // Example
            ],
            [
                'name' => 'Tidak Menyerah',
                'description' => 'Mencoba lagi setelah gagal 3 kali.',
                'icon_path' => 'badges/persistence.png',
                'type' => 'effort',
                'requirement_type' => 'attempts',
                'requirement_value' => 3,
            ],
            [
                'name' => 'Belajar Konsisten',
                'description' => 'Login selama 3 hari berturut-turut.',
                'icon_path' => 'badges/consistency.png',
                'type' => 'consistency',
                'requirement_type' => 'login_streak',
                'requirement_value' => 3,
            ],
        ];

        foreach ($badges as $badge) {
            \App\Models\Badge::create($badge);
        }
    }
}
