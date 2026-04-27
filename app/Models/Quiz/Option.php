<?php

namespace App\Models\Quiz;

use App\Enums\OptionLabel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'label' => OptionLabel::class,
        ];
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
