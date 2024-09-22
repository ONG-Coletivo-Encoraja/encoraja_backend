<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GraphicsController;
use App\Http\Controllers\Api\InscriptionController;
use App\Http\Controllers\Api\LoggedUserController;
use App\Http\Controllers\Api\ReportAdminController;
use App\Http\Controllers\Api\ReportsCsvController;
use App\Http\Controllers\Api\RequestVolunteerController;
use App\Http\Controllers\Api\ReviewController;
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
    Route::delete('/users/me', [LoggedUserController::class, 'destroy']); // apaga o proprio perfil
    
    Route::get('/users/events', [EventController::class, 'getAll']); // lista todos os eventos
    Route::get('/users/events/{event}', [EventController::class, 'getById']); // busca evento pelo id

    Route::post('/inscription', [InscriptionController::class, 'store']); // usuário logado faz inscrição no nome dele próprio
    Route::delete('/inscription/{id}', [InscriptionController::class,  'destroy']); // usuário logado cancela suas próprias inscrições
    Route::get('/myInscriptions', [InscriptionController::class, 'getMyInscriptions']); // minhas inscrições
    Route::get('/inscription/{id}', [InscriptionController::class, 'getById']); // pega inscrição pelo id
    
    Route::post('/reviews', [ReviewController::class, 'store']); // cria uma avaliação
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']); // apaga uma avaliação
    Route::get('/reviews/{id}', [ReviewController::class, 'getByEvent']); // pega avaliações de acordo com 1 evento
    Route::get('/reviews/detail/{id}', [ReviewController::class, 'getById']); // detalhes de 1 avaliação

    Route::put('/report/{id}', [ReportAdminController::class, 'update']); // atualiza report - adm ou volunteer dono do resport
});

Route::group(['middleware' => CheckUserPermission::class.':administrator'], function () {
    Route::get('/graphics/compliance', [GraphicsController::class, 'complianceChart']); 
    Route::get('/graphics/ethnicity', [GraphicsController::class, 'ethnicityChart']); 
    Route::get('/graphics/present', [GraphicsController::class, 'presentEventChart']); 
    Route::get('/graphics/rating', [GraphicsController::class, 'ratingsChart']); 
    Route::get('/graphics/age', [GraphicsController::class, 'ageGroupChart']);

    Route::get('/report/users', [ReportsCsvController::class, 'exportCsvUser']); 
    Route::get('/report/inscriptions', [ReportsCsvController::class, 'exportCsvInscriptionReview']);
    Route::get('/report/events', [ReportsCsvController::class, 'exportCsvEventsReport']);
    Route::get('/report/compliance', [ReportsCsvController::class, 'exportCsvComplianceReport']); 

    Route::get('/admin/users', [UserController::class, 'index']); // listar todos os usuários
    Route::put('/admin/users/{user}', [UserController::class, 'update']); // editar permissão de um usuário
    Route::get('/admin/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico
    
    Route::put('/admin/event/{event}', [EventController::class, 'update']); // atualiza evento
    Route::post('/admin/event', [EventController::class, 'store']); // criar evento adm
    Route::delete('/admin/event/{event}', [EventController::class, 'destroy']); // deleta evento

    Route::get('/admin/inscriptions/event/{event}', [InscriptionController::class, 'getByEventId']); // pega as inscrições de acordo com um evento
    Route::put('/admin/inscriptions/{inscription}', [InscriptionController::class, 'update']);

    Route::get('/admin/requestsVolunteer', [RequestVolunteerController::class, 'getAllRequests']); // pega todas as requests
    Route::put('/admin/requestsVolunteer/{id}', [RequestVolunteerController::class, 'updateStatus']); // atualiza status da request

    Route::get('/admin/report', [ReportAdminController::class, 'getAll']); // pega todos os relatorios
    Route::get('/admin/report/event/{id}', [ReportAdminController::class, 'getByEvent']); // pega relatorio de 1 evento
    Route::get('/admin/report/{id}', [ReportAdminController::class, 'getById']); // pega um relatorio por id
});

Route::group(['middleware' => CheckUserPermission::class.':volunteer'], function () {
    Route::get('/volunteer/users/{user}', [UserController::class, 'show']); // detalhes de um usuário especifico

    Route::get('/volunteer/inscriptions/event/{event}', [InscriptionController::class, 'getByEventId']); // pega as inscrições de acordo com um evento
    Route::put('/volunteer/inscriptions/{inscription}', [InscriptionController::class, 'update']);
    
    Route::post('/volunteer/event', [EventController::class, 'storeVolunteer']);
    Route::put('/volunteer/event/{event}', [EventController::class, 'updateVolunteer']);

    Route::put('/volunteer/requestsVolunteer', [RequestVolunteerController::class, 'update']); // atualizar dados de voluntáriado

    Route::post('/volunteer/report', [ReportAdminController::class, 'store']); // cria um report para o admin
    Route::get('/volunteer/report', [ReportAdminController::class, 'getAll']); // pega todos os relatorios
    Route::get('/volunteer/report/event/{id}', [ReportAdminController::class, 'getByEvent']); // pega relatorio de 1 evento
    Route::get('/volunteer/report/{id}', [ReportAdminController::class, 'getById']); // pega um relatorio por id

    Route::get('/volunteer/my/event', [EventController::class, 'getByLogged']); //pega evento que o user logado é responsável
    
});

Route::group(['middleware' => CheckUserPermission::class.':beneficiary'], function () {
    Route::post('/beneficiary/requestsVolunteer', [RequestVolunteerController::class, 'store']); // criar solicitação de voluntário
});
