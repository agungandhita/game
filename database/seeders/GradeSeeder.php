<?php

namespace Database\Seeders;

use App\Models\Quiz\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            Grade::create([
                'id' => Str::uuid(),
                'name' => "Kelas $i",
                'order' => $i,
            ]);
        }
    }
}
