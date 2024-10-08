<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class LoggedUserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function me() : JsonResponse
    {
        try {
            $userResource = $this->userService->me();
            return response()->json($userResource, 200);
        } catch (\Exception $e) {
            return response()->json(['Erro ao trazer informações do usuário!' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        try {
            $userResource = $this->userService->updateLoggedUser($request->validated());

            return response()->json([
                'status' => true,
                'user' => $userResource,
                'message' => "Usuário atualizado com sucesso!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(): JsonResponse
    {
        try {
            $this->userService->deleteUser();

            return response()->json([
                'status' => true,
                'message' => "Usuário apagado com sucesso!",
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
