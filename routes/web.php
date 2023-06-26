<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Crud\AdminCrudController;

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

        Route::prefix("admins")->group(function () {
            Route::get("/", [AdminCrudController::class, "index"])->name("admin.admins");
            Route::get("/create", [AdminCrudController::class, "create"])->name("admin.admins.create");
            Route::post("/createStore", [AdminCrudController::class, "createStore"])->name("admin.admins.createStore");
            Route::get("/update/{admin}", [AdminCrudController::class, "update"])->name("admin.admins.update");
            Route::put("/updateStore/{admin}", [AdminCrudController::class, "updateStore"])->name("admin.admins.updateStore");
            Route::delete("/delete/{admin}", [AdminCrudController::class, "delete"])->name("admin.admins.delete");
        });
    });
});
