<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordCheckRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class InterPasswordToPassController extends Controller
{
    use ApiResponseTrait;

    public function PasswordChick(PasswordCheckRequest $request)
    {

        $user = JWTAuth::user();
        if (password_verify($request->password, $user->password)) {
            return $this->successResponse(true, 'the password is true');
        }
        return $this->errorResponse('the password is false', 401);
    }
}
