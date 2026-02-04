<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = ['name', 'category', 'image_path', 'unlock_points'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
