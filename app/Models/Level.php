<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['world_id', 'title', 'sequence', 'points_reward'];

    public function world()
    {
        return $this->belongsTo(World::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
}
