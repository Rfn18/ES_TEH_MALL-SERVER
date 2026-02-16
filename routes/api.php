<?php

use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;


// Route::post('register', [UserAuthController::class, 'register']);
// Route::post('login', [UserAuthController::class, 'login']);
// Route::post('logout', [UserAuthController::class, 'logout'])->middleware("auth:sanctum");

Route::apiResource('/jenis', JenisController::class);
Route::apiResource('/menu', MenuController::class);