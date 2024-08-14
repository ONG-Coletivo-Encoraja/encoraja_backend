<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\InscriptionController;
use App\Http\Controllers\Api\LoggedUserController;
use App\Http\Controllers\Api\RequestVolunteerController;
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
    
    
    Route::get('/users/events', [EventController::class, 'getAll']); // lista todos os eventos
    Route::get('/users/events/{event}', [EventController::class, 'getById']); // busca evento pelo id

    Route::post('/inscription', [InscriptionController::class, 'store']); // usuário logado faz inscrição no nome dele próprio
    Route::delete('/inscription/{id}', [InscriptionController::class,  'destroy']); // usuário logado cancela suas próprias inscrições
    Route::get('/myInscriptions', [InscriptionController::class, 'getMyInscriptions']); // minhas inscrições
    Route::get('/inscription/{id}', [InscriptionController::class, 'getById']);
});

Route::group(['middleware' => CheckUserPermission::class.':administrator'], function () {
    Route::get('/admin/users', [UserController::class, 'index']); // listar todos os usuários
    Route::put('/admin/users/{user}', [UserController::class, 'update']); // editar permissão de um usuário
    Route::get('/admin/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico
    
    Route::put('/admin/event/{event}', [EventController::class, 'update']); // atualiza evento
    Route::post('/admin/event', [EventController::class, 'store']); // criar evento adm
    Route::delete('/admin/event/{event}', [EventController::class, 'destroy']); // deleta evento

    Route::get('/admin/inscriptions/event/{event}', [InscriptionController::class, 'getByEventId']); // pega as inscrições de acordo com um evento

    Route::get('/admin/requestsVolunteer', [RequestVolunteerController::class, 'getAllRequests']);
});

Route::group(['middleware' => CheckUserPermission::class.':volunteer'], function () {
    Route::get('/volunteer/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico

    Route::get('/volunteer/inscriptions/event/{event}', [InscriptionController::class, 'getByEventId']); // pega as inscrições de acordo com um evento
    
    Route::put('/volunteer/requestVolunteer', [RequestVolunteerController::class, 'update']); // atualizar dados de voluntáriado
});

Route::group(['middleware' => CheckUserPermission::class.':beneficiary'], function () {
    Route::post('/beneficiary/requestVolunteer', [RequestVolunteerController::class, 'store']); // criar solicitação de voluntário
});
