<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Student = 'student';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Student => 'Siswa',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Admin => 'text-purple-700 bg-purple-100',
            self::Student => 'text-blue-700 bg-blue-100',
        };
    }
}
