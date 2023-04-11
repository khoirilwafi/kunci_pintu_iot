<?php

use App\Http\Controllers\Door\AuthController;
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

Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/get-doors', [AuthController::class, 'getDoor'])->middleware('auth:sanctum');
