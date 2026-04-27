<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            GradeSeeder::class,
            LevelSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
