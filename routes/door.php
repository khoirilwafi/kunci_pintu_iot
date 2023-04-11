<?php

use Illuminate\Http\Request;
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

Route::post('/doorLogin', [AuthController::class, 'doorLogin']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
