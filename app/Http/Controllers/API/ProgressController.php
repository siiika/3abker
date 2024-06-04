<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Traits\ApiResponseTrait;

class ProgressController extends Controller
{
    use ApiResponseTrait;
    /*
    make the video seen by adding the data to uservideo
    'user_id',
        'video_id',
        'watched_at',

     */
    public function markVideoAsWatched(Request $request, $videoId)
    {

        $user = JWTAuth::user();
        $video = Video::findOrFail($videoId);

        // Check if the video is already marked as watched by the user
        if ($user->watchedVideos()->where('video_id', $videoId)->exists()) {
            return $this->successResponse(null, 'Video already marked as watched');
        }

        // Attach the video to the user and include additional data for the pivot table
        $user->watchedVideos()->attach($videoId, ['watched_at' => now()]);

        return $this->successResponse(null, 'Video marked as watched');
    }





    public function calculateProgress($courseId)
    {


        // Retrieving the authenticated user
        $user = JWTAuth::user();

        // Finding the course by the provided course ID
        $course = Course::findOrFail($courseId);

        // Counting total number of videos in the course
        $totalVideos = $course->videos->count();

        // Counting the number of videos watched by the user in the course
        $watchedVideos = $user->watchedVideos->where('course_id', $courseId)->count();

        // Calculating the progress percentage
        if ($totalVideos > 0) {
            $progressPercentage = ($watchedVideos / $totalVideos) * 100;
        } else {
            $progressPercentage = 0;
        }

        // Returning the progress percentage as JSON response
        return $this->successResponse(['progress' => $progressPercentage]);
    }
}
