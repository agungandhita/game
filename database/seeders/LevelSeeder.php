<?php

namespace Database\Seeders;

use App\Models\Quiz\Grade;
use App\Models\Quiz\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::all();
        foreach ($grades as $grade) {
            for ($i = 1; $i <= 3; $i++) {
                Level::create([
                    'id' => Str::uuid(),
                    'grade_id' => $grade->id,
                    'name' => "Level $i",
                    'order' => $i,
                    'is_active' => true,
                    'time_per_question' => 30,
                ]);
            }
        }
    }
}
