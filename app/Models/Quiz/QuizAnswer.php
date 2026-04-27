<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_id',
        'question_id',
        'selected_option_id',
        'is_correct',
        'is_timeout',
        'time_spent',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'is_timeout' => 'boolean',
            'time_spent' => 'integer',
        ];
    }

    public function session()
    {
        return $this->belongsTo(QuizSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }
}
