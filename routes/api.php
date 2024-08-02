<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiProtectedRoute;
use Illuminate\Support\Facades\Route;


Route::post('auth/login', [AuthController::class, 'login']);


Route::group(['middleware'=> ApiProtectedRoute::class], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    
    Route::post('/users', [UserController::class,'store']); // http://127.0.0.1:8000/api/users
    Route::get('/users', [UserController::class, 'index']); // http://127.0.0.1:8000/api/users?page=2
    Route::get('/users/{user}',  [UserController::class, 'show']); // http://127.0.0.1:8000/api/users/1
    Route::put('/users/{user}', [UserController::class,'update']); // http://127.0.0.1:8000/api/users/1
    Route::delete('/users/{user}', [UserController::class,'destroy']); // http://127.0.0.1:8000/api/users/1
}); 