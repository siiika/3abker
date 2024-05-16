<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CourseEnrollmentController extends Controller
{
    use ApiResponseTrait;
    public function enroll(Request $request, $courseId)
    {
        // Get the authenticated user
        $user = JWTAuth::user();

        // Check if the course exists
        $course = Course::findOrFail($courseId);

        // Manually insert the record into the pivot table
        DB::table('course_user')->insert([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return $this->successResponse([
            'user_id' => $user->id,
            'course_id' => $course->id
        ], 'User enrolled in course successfully');
    }
}
