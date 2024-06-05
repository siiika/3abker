<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserVideo;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class VideoController extends Controller
{
    use ApiResponseTrait;
    public function videosByCourse($courseId)
    {
        $user = JWTAuth::user();

        // Check if the user is enrolled in the course
        if (!$user->courses->contains($courseId)) {
            return $this->errorResponse(['error' => 'User is not enrolled in this course.'], 403);
        }
        $course = Course::findOrFail($courseId);
        $videos = $course->videos;


        // Fetch videos for the authenticated user
        $data = UserVideo::where('user_id', $user->id)->get();

        return $this->successResponse([$videos, $data], 'this is all videos in the course');
        // return response()->json($videos);
    }

    public function videoInCourse($courseId, $videoId)
    {

        $user = JWTAuth::user();

        // Check if the user is enrolled in the course
        if (!$user->courses->contains($courseId)) {
            return $this->errorResponse(['error' => 'User is not enrolled in this course.'], 403);
        }
        $course = Course::findOrFail($courseId);
        $video = $course->videos()->findOrFail($videoId);

        return response()->json($video);
    }
}
