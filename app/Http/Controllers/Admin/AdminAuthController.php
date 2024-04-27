<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use ApiResponseTrait;
use App\Traits\ApiResponseTrait as TraitsApiResponseTrait;

class AdminAuthController extends Controller
{
    use TraitsApiResponseTrait;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }


    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'father_name' => 'required|string|max:255', // Apply conditional rule
            'age' => 'required|integer|min:1',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->password),
            'phone' => $request->get('phone'),
            'father_name' => $request->get('father_name'), // Apply conditional rule
            'age' => $request->get('age'),
            'role' => 'admin'
        ]);

        $token = JWTAuth::fromUser($user);
        return $this->successResponse([
            'user' => $user,
            'role' => $user->role,
            'token' => $token,
        ], 'admin successfully registered');
    }
}
