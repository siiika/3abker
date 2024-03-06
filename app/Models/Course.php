<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'age_group',
        'current_enroll',
        'category',

    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
