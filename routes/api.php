<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Setting\SettingController;
use Illuminate\Support\Facades\Auth;

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

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('users/{user}/verification-email', [UserController::class, 'verificationEmail'])->name('verification-email-users');
    Route::middleware('auth:sanctum')->group(function () {
            Route::post('auth/login', [AuthController::class, 'index']);
            Route::get('/auth/verify', [AuthController::class, 'verify']);
            Route::get('/auth/{user}/login-as', [AuthController::class, 'loginAs']);
            Route::get('/auth/logout/{user?}', [AuthController::class, 'logout']);
            Route::post('update-password/{id}', [UserController::class, 'updatePassword']);
        Route::prefix('users')->middleware('can:read-users')->group(function () {
            Route::get('get-data', [UserController::class, 'getData']);
            Route::get('get-roles', [UserController::class, 'getRoles']);
            Route::post('store', [UserController::class, 'store'])->name('users.store')->middleware('can:create-users');
            Route::get('{user}/show', [UserController::class, 'show']);
            Route::post('{user}/update', [UserController::class, 'update'])->middleware('can:update-users');
            Route::delete('{user}', [UserController::class, 'destroy'])->middleware('can:delete-users');
            Route::get('{user}/access', [UserController::class, 'getAccess'])->middleware('can:update-users');
            Route::post('{user}/access', [UserController::class, 'storeAccess'])->middleware('can:update-users');
        });
        Route::get('users/{user}/validation-email', [UserController::class, 'validationSendEmail'])->name('validation-email-users');
        

        Route::prefix('roles')->middleware('can:read-roles')->group(function () {
            Route::get('', [RoleController::class, 'getData']);
            Route::post('', [RoleController::class, 'store'])->middleware('can:create-roles');
            Route::delete('{role}', [RoleController::class, 'destroy'])->middleware('can:delete-roles');
            Route::get('{role}', [RoleController::class, 'show']);
            Route::post('{role}', [RoleController::class, 'update'])->middleware('can:update-roles');
            Route::get('{role}/get-permissions', [RoleController::class, 'getPermissions']);
            Route::post('{role}/change-permissions', [RoleController::class, 'changePermissions']);
        });
    });

    Route::get('settings', [SettingController::class, 'getSetting']);
});

// Requiring partials
require_once __DIR__ . '/partials/api/dashboard.php';
require_once __DIR__ . '/partials/api/master.php';
require_once __DIR__ . '/partials/api/setting.php';
require_once __DIR__ . '/partials/api/general.php';
require_once __DIR__ . '/partials/api/log.php';
require_once __DIR__ . '/partials/api/portal.php';
require_once __DIR__ . '/partials/api/lecture.php';
require_once __DIR__ . '/partials/api/student_affairs.php';
require_once __DIR__ . '/partials/api/reports.php';
require_once __DIR__ . '/partials/api/feeder.php';
// Role Student
require_once __DIR__ . '/partials/api/student/general.php';
require_once __DIR__ . '/partials/api/student/student.php';
// Role Employee
require_once __DIR__ . '/partials/api/lecturer/lecturer.php';
require_once __DIR__ . '/partials/api/lecturer/general.php';
