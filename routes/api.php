<?php

use App\Http\Controllers\Api\AccessController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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


// login
Route::post('/login', [AuthController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {

    // logout
    Route::get('/logout', [AuthController::class, 'logout']);

    // update user profile
    Route::post('/update-profile', [UserController::class, 'updateProfile']);

    // verify otp via email
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

    // get user avatar
    Route::get('/avatar/{file}', [UserController::class, 'getAvatar']);

    // get user access
    Route::get('/my-access', [AccessController::class, 'myAccess']);

    // get door at office
    Route::get('/get-door', [AccessController::class, 'getOfficeDoor']);

    // check access
    Route::get('/verify-access/{door_id}', [AccessController::class, 'verifyAccess']);

    // remote access
    Route::post('/remote-access', [AccessController::class, 'remoteAccess']);
});
