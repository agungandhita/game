<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'grade_id',
        'name',
        'order',
        'is_active',
        'time_per_question',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'time_per_question' => 'integer',
        ];
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function levelResults()
    {
        return $this->hasMany(LevelResult::class);
    }

    public function quizSessions()
    {
        return $this->hasMany(QuizSession::class);
    }
}
