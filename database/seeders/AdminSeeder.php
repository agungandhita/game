<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'Administrator',
            'nickname' => 'Admin',
            'email' => 'admin@quiz.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);
    }
}
