<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avatars = [
            // Hewan
            ['name' => 'Kucing Cerdik', 'category' => 'Hewan', 'image_path' => 'avatars/cat.png', 'unlock_points' => 0],
            ['name' => 'Panda Pintar', 'category' => 'Hewan', 'image_path' => 'avatars/panda.png', 'unlock_points' => 500],
            ['name' => 'Burung Hantu Bijak', 'category' => 'Hewan', 'image_path' => 'avatars/owl.png', 'unlock_points' => 1000],

            // Robot
            ['name' => 'Robo-1', 'category' => 'Robot', 'image_path' => 'avatars/robot1.png', 'unlock_points' => 0],
            ['name' => 'Cyber Bot', 'category' => 'Robot', 'image_path' => 'avatars/robot2.png', 'unlock_points' => 800],

            // Pahlawan
            ['name' => 'Super Math', 'category' => 'Pahlawan', 'image_path' => 'avatars/hero1.png', 'unlock_points' => 1500],
            ['name' => 'Wonder Count', 'category' => 'Pahlawan', 'image_path' => 'avatars/hero2.png', 'unlock_points' => 2000],
        ];

        foreach ($avatars as $avatar) {
            \App\Models\Avatar::create($avatar);
        }
    }
}
