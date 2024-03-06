<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\certificate;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

use function Laravel\Prompts\error;

class CertificateController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',


        ]);


        // Retrieve the course name associated with the user
        $courseName = $user->courses()->pluck('title')->first();

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'course_name' => $courseName,
            'degree' => 100
        ]);

        return $this->show();;
    }


    public function show()
    {
        $user = JWTAuth::user();
        $certificate = $user->certificate;
        if ($certificate) {
            // Return the certificate as JSON response
            return $this->successResponse($certificate, "user certificate");
        } else {
            // If the user does not have a certificate, return a 404 response
            return $this->errorResponse('Certificate not found', 404);
        }
    }
}
