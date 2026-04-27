<?php

namespace App\Models\Quiz;

use App\Enums\SessionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'level_id',
        'started_at',
        'finished_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'status' => SessionStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'session_id');
    }
}
