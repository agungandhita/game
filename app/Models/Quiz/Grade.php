<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'order',
    ];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }
}
