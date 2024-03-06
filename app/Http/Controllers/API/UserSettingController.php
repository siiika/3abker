<?php

namespace App\Http\Controllers\API;



use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;



class UserSettingController extends Controller
{

    use ApiResponseTrait;
    public function getuser()
    {
        $user = JWTAuth::user();

        // Check if the user is authenticated
        if (!$user) {
            return $this->errorResponse('Unauthenticated', 401);
        }
        return response()->json($user->makeHidden(['password', 'id', 'social_type', 'email_verified_at', 'password_reset_token', 'created_at', 'updated_at', 'social_id', 'sosial_tybe']));
    }

    public function update(UpdateUserRequest $request)
    {
        // Retrieve the authenticated user using the Auth facade
        $user = JWTAuth::user();

        // Check if the user is authenticated
        if (!$user) {
            return $this->errorResponse('Unauthenticated', 401);
        }

        $validatedData = $request->validated();

        // Encrypt the password if it's included in the request and not empty
        if (isset($validatedData['password']) && !empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        // Update user record with validated data
        $user->update($validatedData);

        // Update the user's information using fill and save methods
        // $user->fill($request->only(['name', 'email', 'age', 'father_name', 'phone']));
        // $user->password = bcrypt($request->input('password'));
        // $user->save();

        // Return a success response
        return $this->successResponse(null, 'User information updated successfully');
    }

    public function destroy()
    {
        // Retrieve the authenticated user using the Auth facade
        $user = JWTAuth::user();

        // Check if the user is authenticated
        if (!$user) {
            return $this->errorResponse('Unauthenticated', 401);
        }

        // Delete the user account
        $user->delete();

        // Return a success response
        return $this->successResponse(null, 'User account deleted successfully');
    }
}
