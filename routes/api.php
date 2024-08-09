<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LoggedUserController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiProtectedRoute;
use App\Http\Middleware\CheckUserPermission;
use Illuminate\Support\Facades\Route;

//ROTAS DESLOGADAS
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']); // cadastro

//ROTAS LOGADAS 

// rotas de todos os users
Route::group(['middleware' => ApiProtectedRoute::class], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']); // logout
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    Route::get('/users/me', [LoggedUserController::class, 'me']); // detalhes do user logado
    Route::put('/users/me', [LoggedUserController::class, 'update']); // edita o usuário logado
    
    // Route::delete('/users/{user}', [UserController::class, 'destroy']); // deleta um usuário
});

// rotas de adm
Route::group(['middleware' => CheckUserPermission::class.':administrator'], function () {
    Route::get('/admin/users', [UserController::class, 'index']); // listar todos os usuários
    Route::put('/admin/users/{user}', [UserController::class, 'update']); // editar permissão de um usuário
    Route::get('/admin/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico

    Route::post('/admin/event', [EventController::class, 'store']);
});

// rotas de volunteer
Route::group(['middleware' => CheckUserPermission::class.':volunteer'], function () {
    Route::get('volunteer/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico
});

// rotas de beneficiary
Route::group(['middleware' => CheckUserPermission::class.':beneficiary'], function () {
    
});
