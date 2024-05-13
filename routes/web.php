<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Crud\AdminCrudController;
use App\Http\Controllers\Admin\Crud\UserCrudController;
use App\Http\Controllers\Admin\Crud\UserReviewCrudController;
use App\Http\Controllers\Admin\Crud\UserCommentCrudController;
use App\Http\Controllers\Admin\Crud\ForumCrudController;
use App\Http\Controllers\Admin\Crud\ForumTitleCrudController;
use App\Http\Controllers\Admin\Crud\ForumReportCrudController;
use App\Http\Controllers\Admin\Crud\SchoolCrudController;
use App\Http\Controllers\Admin\Crud\MajorCrudController;
use App\Http\Controllers\Admin\Crud\CourseCrudController;
use App\Http\Controllers\Admin\Crud\TeacherCrudController;
use App\Http\Controllers\Admin\NotificationController;

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
    return "Kerim senin hayatını sikiyim!";
});

Route::prefix("admin")->group(function () {
    Route::post('/admin/import', [Dashboard::class, 'importExcel'])->name('admin.import');
    Route::middleware(["guest"])->group(function () {
        Route::get("login", [AuthController::class, "login"])->name("admin.login");
        Route::post("loginStore", [AuthController::class, "loginStore"])->name("admin.loginStore");
    });

    Route::middleware(['auth:admin'])->group(function () {
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

        Route::prefix("forums")->group(function () {
            Route::get("/", [ForumCrudController::class, "index"])->name("admin.forums");
            Route::get("/create", [ForumCrudController::class, "create"])->name("admin.forums.create");
            Route::post("/createStore", [ForumCrudController::class, "createStore"])->name("admin.forums.createStore");
            Route::get("/update/{post}", [ForumCrudController::class, "update"])->name("admin.forums.update");
            Route::put("/updateStore/{post}", [ForumCrudController::class, "updateStore"])->name("admin.forums.updateStore");
            Route::delete("/delete/{post}", [ForumCrudController::class, "delete"])->name("admin.forums.delete");

            Route::prefix("titles")->group(function () {
                Route::get("/", [ForumTitleCrudController::class, "index"])->name("admin.forums.titles");
                Route::get("/create", [ForumTitleCrudController::class, "create"])->name("admin.forums.titles.create");
                Route::post("/createStore", [ForumTitleCrudController::class, "createStore"])->name("admin.forums.titles.createStore");
                Route::get("/update/{postTitle}", [ForumTitleCrudController::class, "update"])->name("admin.forums.titles.update");
                Route::put("/updateStore/{postTitle}", [ForumTitleCrudController::class, "updateStore"])->name("admin.forums.titles.updateStore");
                Route::delete("/delete/{postTitle}", [ForumTitleCrudController::class, "delete"])->name("admin.forums.titles.delete");
            });

            Route::prefix("reports")->group(function () {
                Route::get("/", [ForumReportCrudController::class, "index"])->name("admin.forums.reports");
                Route::delete("/delete/{report}", [ForumReportCrudController::class, "delete"])->name("admin.forums.reports.delete");
            });
        });

        Route::prefix("users")->group(function () {
            Route::prefix("reviews")->group(function () {
                Route::get("/", [UserReviewCrudController::class, "index"])->name("admin.users.reviews");
                Route::delete("/delete/{review}", [UserReviewCrudController::class, "delete"])->name("admin.users.reviews.delete");
            });

            Route::prefix("comments")->group(function () {
                Route::get("/", [UserCommentCrudController::class, "index"])->name("admin.users.comments");
                Route::delete("/delete/{comment}", [UserCommentCrudController::class, "delete"])->name("admin.users.comments.delete");
            });

            Route::get("/", [UserCrudController::class, "index"])->name("admin.users");
            Route::get("/create", [UserCrudController::class, "create"])->name("admin.users.create");
            Route::post("/createStore", [UserCrudController::class, "createStore"])->name("admin.users.createStore");
            Route::get("/update/{user}", [UserCrudController::class, "update"])->name("admin.users.update");
            Route::put("/updateStore/{user}", [UserCrudController::class, "updateStore"])->name("admin.users.updateStore");
            Route::delete("/delete/{user}", [UserCrudController::class, "delete"])->name("admin.users.delete");
        });

        Route::prefix("schools")->group(function () {
            Route::get("/", [SchoolCrudController::class, "index"])->name("admin.schools");
            Route::get("/create", [SchoolCrudController::class, "create"])->name("admin.schools.create");
            Route::post("/createStore", [SchoolCrudController::class, "createStore"])->name("admin.schools.createStore");
            Route::get("/update/{school}", [SchoolCrudController::class, "update"])->name("admin.schools.update");
            Route::put("/updateStore/{school}", [SchoolCrudController::class, "updateStore"])->name("admin.schools.updateStore");
            Route::delete("/delete/{school}", [SchoolCrudController::class, "delete"])->name("admin.schools.delete");
        });

        Route::prefix("majors")->group(function () {
            Route::get("/", [MajorCrudController::class, "index"])->name("admin.majors");
            Route::get("/create", [MajorCrudController::class, "create"])->name("admin.majors.create");
            Route::post("/createStore", [MajorCrudController::class, "createStore"])->name("admin.majors.createStore");
            Route::get("/update/{major}", [MajorCrudController::class, "update"])->name("admin.majors.update");
            Route::put("/updateStore/{major}", [MajorCrudController::class, "updateStore"])->name("admin.majors.updateStore");
            Route::delete("/delete/{major}", [MajorCrudController::class, "delete"])->name("admin.majors.delete");
        });

        Route::prefix("courses")->group(function () {
            Route::get("/", [CourseCrudController::class, "index"])->name("admin.courses");
            Route::get("/create", [CourseCrudController::class, "create"])->name("admin.courses.create");
            Route::post("/createStore", [CourseCrudController::class, "createStore"])->name("admin.courses.createStore");
            Route::get("/update/{course}", [CourseCrudController::class, "update"])->name("admin.courses.update");
            Route::put("/updateStore/{course}", [CourseCrudController::class, "updateStore"])->name("admin.courses.updateStore");
            Route::delete("/delete/{course}", [CourseCrudController::class, "delete"])->name("admin.courses.delete");
        });

        Route::prefix("teachers")->group(function () {
            Route::get("/", [TeacherCrudController::class, "index"])->name("admin.teachers");
            Route::get("/create", [TeacherCrudController::class, "create"])->name("admin.teachers.create");
            Route::post("/createStore", [TeacherCrudController::class, "createStore"])->name("admin.teachers.createStore");
            Route::get("/update/{teacher}", [TeacherCrudController::class, "update"])->name("admin.teachers.update");
            Route::put("/updateStore/{teacher}", [TeacherCrudController::class, "updateStore"])->name("admin.teachers.updateStore");
            Route::delete("/delete/{teacher}", [TeacherCrudController::class, "delete"])->name("admin.teachers.delete");
        });

        Route::get("/send-notification", [NotificationController::class, "index"])->name("admin.sendNotification");
        Route::post("/send-notification", [NotificationController::class, "sendNotification"])->name("admin.sendNotificationStore");
    });
});
