<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\Profile\MajorController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Api\Service\MapController;
use App\Http\Controllers\Api\Post\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forget', [AuthController::class, 'forget']);
Route::post('/auth/forget/check', [AuthController::class, 'forgetCheck']);
Route::post('/auth/forget/control', [AuthController::class, 'forgetControl']);
Route::post('/unauthenticated', function () {
    return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 403);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('otp', [AuthController::class, 'otp']);
        Route::post('otp/check', [AuthController::class, 'otpCheck']);
        Route::post('info', [AuthController::class, 'info']);
    });

    Route::prefix('profile')->group(function () {
        Route::post('/', [ProfileController::class, 'index']);
        Route::post('/avatar', [AvatarController::class, 'index']);
        Route::post('/avatar/set', [AvatarController::class, 'store']);
        Route::post('/avatar/update', [AvatarController::class, 'update']);

        Route::post('/majors', [MajorController::class, 'index']);
    });

    Route::prefix('/location')->group(function () {
        Route::post('/settings', [MapController::class, 'index']);
        Route::post('/save', [MapController::class, 'store']);
        Route::post('/get', [MapController::class, 'getMap']);
    });

    Route::prefix('post')->group(function () {
       Route::post('/list', [PostController::class, 'index']);
       Route::post('/create', [PostController::class, 'store']);
       Route::post('/update/{id}', [PostController::class, 'update']);
       Route::post('/delete/{id}', [PostController::class, 'destroy']);
    });
});
