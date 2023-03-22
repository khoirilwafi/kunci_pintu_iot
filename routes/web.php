<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use GuzzleHttp\Middleware;

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
    return view('oret');
});

// login
Route::get('/login', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');

// logout
Route::get('/logout', [AuthController::class, 'logout']);

// forgot password
Route::get('/forgot-password', [AuthController::class, 'requestResetPAssword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'handleResetPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->middleware('guest')->name('password.update');

// verify email
Route::get('/email-verification', [VerificationController::class, 'index'])->name('verification.notice');
Route::post('/email-verification', [VerificationController::class, 'verify']);





// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified']);

// route group for moderator
Route::middleware(['auth', 'verified', 'moderator'])->group(function () {

    // get all office
    Route::get('/offices', [DashboardController::class, 'offices']);

    // get all operator
    Route::get('/operators', [DashboardController::class, 'operators']);
});

// route group for operator
Route::middleware(['auth', 'verified', 'operator'])->group(function () {

    // get all users
    Route::get('/users', [DashboardController::class, 'users']);

    // get all doors
    Route::get('/doors', [DashboardController::class, 'doors']);

    // get all schedules
    Route::get('/schedules', [DashboardController::class, 'scedules']);

    // get all guest
    Route::get('/guests', [DashboardController::class, 'guests']);
});

// account
Route::get('my-account', [DashboardController::class, 'my_account'])->middleware(['auth', 'verified']);
Route::get('my-account/avatar/{file}', [UserController::class, 'getAvatar'])->middleware(['auth', 'verified']);
