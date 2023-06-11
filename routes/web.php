<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "Hello World!";
});

Route::prefix("admin")->group(function () {
    Route::middleware(["guest"])->group(function () {
        Route::get("login", [AuthController::class, "login"])->name("admin.login");
        Route::post("loginStore", [AuthController::class, "loginStore"])->name("admin.loginStore");
    });
    Route::middleware(["auth:admin"])->group(function () {
        Route::get("logout", [AuthController::class, "adminLogout"])->name("admin.logout");
        Route::get("changePassword", [AuthController::class, "changePassword"])->name("admin.changePassword");
        Route::post("changePassword", [AuthController::class, "changePasswordStore"])->name("admin.changePasswordStore");
        Route::get("/", [Dashboard::class, "index"])->name('admin.dashboard');
    });
});
