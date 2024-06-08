<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class SocialiteContoller extends Controller
{
    use ApiResponseTrait;
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Retrieve user data from Google
        $googleUser = Socialite::driver('google')->user();

        // Check if the user exists in your database based on email
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            // User exists, log them in
            $token = JWTAuth::fromUser($existingUser);
            return $this->successResponse(['token' => $token], 'the uer is already regesterd');
        } else {
            // User does not exist, create a new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make('my-google'), // Generate a random password
                'social_type' => 'google', // Indicate the type of social login
                'phone' => null, // Initialize with null values
                'father_name' => null,
                'age' => null

            ]);

            $token = JWTAuth::fromUser($user);
            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'User successfully registered');
        }

        // Redirect the user to their intended destination
        return redirect()->intended('/');
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        // Retrieve user data from Google
        $facebookUser = Socialite::driver('facebook')->user();

        // Check if the user exists in your database based on email
        $existingUser = User::where('email', $facebookUser->getEmail())->first();

        if ($existingUser) {
            // User exists, log them in
            $token = JWTAuth::fromUser($existingUser);
            return $this->successResponse(['token' => $token], 'the uer is already regesterd');
        } else {
            // User does not exist, create a new user
            $user = User::create([
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'password' => Hash::make('my-facebook'), // Generate a random password
                'social_id' => $facebookUser->getId(), // Store Google's unique user ID
                'social_type' => 'facebook', // Indicate the type of social login
                'phone' => null, // Initialize with null values
                'father_name' => null,
                'age' => null

            ]);

            $token = JWTAuth::fromUser($user);
            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'User successfully registered');
        }

        // Redirect the user to their intended destination
        return redirect()->intended('/');
    }
}
