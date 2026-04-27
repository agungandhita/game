<?php

namespace App\Enums;

enum OptionLabel: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';

    public function label(): string
    {
        return match($this) {
            self::A => 'Pilihan A',
            self::B => 'Pilihan B',
            self::C => 'Pilihan C',
            self::D => 'Pilihan D',
        };
    }

    public function color(): string
    {
        return 'text-blue-600 bg-blue-100';
    }
}
