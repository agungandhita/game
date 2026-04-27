<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelResult extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'level_id',
        'score',
        'stars',
        'total_correct',
        'total_questions',
        'total_timeout',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'stars' => 'integer',
            'total_correct' => 'integer',
            'total_questions' => 'integer',
            'total_timeout' => 'integer',
            'completed_at' => 'datetime',
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
}
