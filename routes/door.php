<?php

use App\Http\Controllers\Door\AuthController;
use App\Http\Controllers\Door\DoorEventController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Door Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Door routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "door" middleware group. Enjoy building your Doors!
|
*/

Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {

    // get signature
    Route::post('/get-signature', [AuthController::class, 'signature']);

    // logout
    Route::get('/logout', [AuthController::class, 'logout']);

    // update door status
    Route::post('/update-status', [DoorEventController::class, 'update_status']);

    // door alert
    Route::post('/alert', [DoorEventController::class, 'alert']);
});
