<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\VerificationController;

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
    return view('welcome');
});

// login
Route::get('/login', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');

// logout
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

// forgot password
Route::get('/forgot-password', [AuthController::class, 'requestResetPAssword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'handleResetPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->middleware('guest')->name('password.update');

// verify email
Route::get('/email-verification', [VerificationController::class, 'index'])->name('verification.notice');
Route::post('/email-verification', [VerificationController::class, 'verify'])->middleware('auth');

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified']);

// route group for moderator
Route::middleware(['auth', 'verified', 'moderator'])->group(function () {

    // get all office
    Route::get('/dashboard/offices', [DashboardController::class, 'offices']);

    // get all operator
    Route::get('/dashboard/operators', [DashboardController::class, 'operators']);
});

// route group for operator
Route::middleware(['auth', 'verified', 'operator'])->group(function () {

    // get all users
    Route::get('/dashboard/users', [DashboardController::class, 'users']);

    // get all doors
    Route::get('/dashboard/doors', [DashboardController::class, 'doors']);

    // get all schedules
    Route::get('/dashboard/schedules', [DashboardController::class, 'schedules']);

    // get all guest
    Route::get('/dashboard/histories', [DashboardController::class, 'histories']);
});

// account
Route::get('/dashboard/my-account', [DashboardController::class, 'my_account'])->middleware(['auth', 'verified']);
Route::get('/dashboard/my-account/avatar/{file}', [DashboardController::class, 'get_avatar'])->middleware(['auth', 'verified']);
