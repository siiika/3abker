<?php

namespace App\Http\Controllers\API;


use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;
    use ApiResponseTrait;
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]); //login, register methods won't go through the api guard
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //attemp return token

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
            'role' => 'user'
        ]);

        $token = JWTAuth::fromUser($user);
        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'User successfully registered');
    }

    public function getaccount()
    {
        return response()->json(auth('api')->user());
    }


    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshToken(Request $request)
    {
        try {
            // Attempt to refresh the token
            $token = JWTAuth::parseToken()->refresh();
            // Return the refreshed token
            return response()->json([
                'success' => true,
                'token' => $token,
                'message' => 'this is the new tokenn',
            ], 200);
            // return $this->successResponse($token, 'this is the new tokenn', 200);
        } catch (\Exception $e) {
            // Something went wrong, return error response
            return $this->errorResponse('Unauthorized', 401);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 //mention the guard name inside the auth fn
        ]);
    }
}
