<?php

namespace App\Enums;

enum StarRating: int
{
    case One = 1;
    case Two = 2;
    case Three = 3;

    public function label(): string
    {
        return match($this) {
            self::One => 'Satu Bintang',
            self::Two => 'Dua Bintang',
            self::Three => 'Tiga Bintang',
        };
    }

    public function color(): string
    {
        return 'text-yellow-400';
    }
}
