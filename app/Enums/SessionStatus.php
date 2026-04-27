<?php

namespace App\Enums;

enum SessionStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::InProgress => 'Sedang Diproses',
            self::Completed => 'Selesai',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::InProgress => 'text-yellow-600 bg-yellow-100',
            self::Completed => 'text-green-600 bg-green-100',
        };
    }
}
