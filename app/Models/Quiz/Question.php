<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'level_id',
        'question_text',
        'order',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
