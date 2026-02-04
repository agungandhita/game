<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class World extends Model
{
    protected $fillable = ['name', 'class', 'slug', 'description'];

    public function levels()
    {
        return $this->hasMany(Level::class)->orderBy('sequence');
    }
}
