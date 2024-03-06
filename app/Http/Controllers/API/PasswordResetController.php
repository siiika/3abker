<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPassword;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    use ApiResponseTrait;

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        // $token = $this->generateToken(); // Generate a unique token here

        // Generate a JWT token for the password reset process
        $token = JWTAuth::fromUser($user);


        $user->notify(new ResetPassword($token));
        return $this->successResponse(null, 'Reset email sent Successfully');
    }

    // private function generateToken()
    // {
    //     return Str::random(60);
    // }

    public function resetPassword(Request $request)
    {

        // Validate input (email, password, password_confirmation, token)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the token matches
        // if (!$user || !Hash::check($request->token, $user->password_reset_token)) {
        //     return response()->json(['message' => 'Invalid email or token'], 400);
        // }

        // Update the user's password
        $user->password = bcrypt($request->password);
        $user->password_reset_token = null; // Clear the password reset token
        $user->save();

        //  notify the user that their password has been reset.
        return $this->successResponse(null, 'Password reset successfully');
    }
}
