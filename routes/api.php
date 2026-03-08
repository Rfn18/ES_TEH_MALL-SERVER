<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\JualController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\StandController;
use App\Http\Controllers\Api\UserManageController;
use App\Http\Controllers\UserAuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])->middleware("auth:sanctum");
Route::apiResource('/stand', StandController::class);  

Route::middleware('auth:sanctum')->group(function () {
     Route::get('jual', [JualController::class, 'index']);
     Route::get('jual/{id}', [JualController::class, 'show']);
     Route::post('jual', [JualController::class, 'storeJual']);
     Route::post('jual-detail', [JualController::class, 'storeDetailJual']);
     Route::get('jual-user', [JualController::class, 'userId']);
     Route::apiResource('/jenis', JenisController::class);
     Route::apiResource('/menu', MenuController::class);
});

Route::middleware("auth:sanctum", AdminMiddleware::class)->group(function () {
     Route::apiResource('/user', UserManageController::class);
     Route::get('/log', [ActivityLogController::class, 'index']);
});

