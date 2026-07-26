<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz\Grade;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('students.json'));
        $students = json_decode($json, true);
        
        $grades = Grade::all()->keyBy('order');

        foreach ($students as $studentData) {
            $order = (int) $studentData['kelas'];
            $grade = $grades->get($order);
            
            // Format nisn and default password
            $nisn = $studentData['nisn'];
            $email = $nisn . '@student.mi-kepoh.sch.id';
            
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $studentData['nama'],
                    'password' => Hash::make($nisn), // Use NISN as default password
                    'role' => UserRole::Student,
                    'grade_id' => $grade ? $grade->id : null,
                ]
            );
        }
    }
}
