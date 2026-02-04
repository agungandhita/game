<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nickname' => 'admin'],
            [
                'grade' => 5,
                'role' => 'admin',
                'total_points' => 0,
                'avatar_id' => null,
            ],
        );
    }
}
