<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id', // Assuming you have a foreign key for the course this video belongs to
        'title',
        'url',
        // Add other fields as needed
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function watchedVideos()
    {
        return $this->hasManyThrough(Video::class, UserVideo::class);
    }


    // public function watchedVideos()
    // {
    //     return $this->hasManyThrough(Video::class, UserVideo::class);
    // }
}
