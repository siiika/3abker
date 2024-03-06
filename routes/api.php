<?php

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\ProgressController;
use App\Http\Controllers\API\SocialiteContoller;
use App\Http\Controllers\API\UserSettingController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\UserTestAnswerController;
use App\Http\Controllers\InterPasswordToPassController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::get('user/getaccount', [AuthController::class, 'getaccount']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
});

Route::post('/forget-password', [PasswordResetController::class, 'forgotPassword']);

Route::get('auth/google', [SocialiteContoller::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialiteContoller::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [SocialiteContoller::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [SocialiteContoller::class, 'handleFacebookCallback']);


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('user/update', [UserSettingController::class, 'update'])->name('user.update');
    Route::post('user/destroy', [UserSettingController::class, 'destroy'])->name('user.destroy');
    Route::get('user/setting', [UserSettingController::class, 'getuser'])->name('user.show');
    Route::post('user/password/check', [InterPasswordToPassController::class, 'PasswordChick'])->name('passsword.chick');
    Route::post('user/refresh-token', [AuthController::class, 'refreshToken']);
    //-------------------------------------------------------------------------------------------
    Route::get('/progress/{courseId}', [ProgressController::class, 'calculateProgress']);
    Route::post('/videos/{videoId}/mark-as-watched', [ProgressController::class, 'markVideoAsWatched']);
    Route::get('/courses/{courseId}/videos', [VideoController::class, 'videosByCourse']);
    Route::get('/courses/{courseId}/videos/{videoId}', [VideoController::class, 'videoInCourse']);
    Route::get('/tests/create/{TestId}', [TestController::class, 'Create']);
    Route::post('/user-tests/{userTestId}/answers', [UserTestAnswerController::class, 'store']);
    Route::get('/user-tests/{userTestId}/answers', [UserTestAnswerController::class, 'show'])->name('user-test.show');
    Route::post('certificate', [CertificateController::class, 'store']);
});


// Route::get('test', function () {

//     $course = Course::find(1);
//     $video = $course->videos;
//     dd($video);
// });