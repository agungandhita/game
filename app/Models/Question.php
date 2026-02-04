<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['level_id', 'content', 'type', 'image_path'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
