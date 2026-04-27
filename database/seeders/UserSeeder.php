<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            // Cek apakah sudah ada berdasarkan email
            if (!User::where('email', "siswa$i@quiz.com")->exists()) {
                // Buat nickname unik jika sudah ada yang sama
                $nickname = "siswa$i";
                if (User::where('nickname', $nickname)->exists()) {
                    $nickname = "siswa_dummy_$i";
                }

                User::create([
                    'name'     => "Siswa $i",
                    'nickname' => $nickname,
                    'email'    => "siswa$i@quiz.com",
                    'password' => Hash::make('password'),
                    'role'     => UserRole::Student,
                ]);
            }
        }
    }
}
