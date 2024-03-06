<?php

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SocialiteContoller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('test', function () {
//     dd('test');
//     $user = User::FIND(1);
//     $course = Course::FIND(1);
//     $user->courses()->attach($course);
//     echo $user;
//     echo '<BR>';
//     echo $course;
// })->name('siiika');

Route::get('auth/google', [SocialiteContoller::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialiteContoller::class, 'handleGoogleCallback']);

Route::get('test', function () {

    $course = Course::find(1);
    $video = $course->videos;
    dd($video);
});
