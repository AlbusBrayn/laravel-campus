<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\Profile\MajorController;

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
Route::post('/unauthenticated', function () {
    return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 403);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::post('/avatar', [AvatarController::class, 'index']);
        Route::post('/avatar/set', [AvatarController::class, 'store']);
        Route::post('/avatar/update', [AvatarController::class, 'update']);

        Route::post('/majors', [MajorController::class, 'index']);
    });
});
