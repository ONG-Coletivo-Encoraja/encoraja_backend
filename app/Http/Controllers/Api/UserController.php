<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        
        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $userDTO = $this->userService->getUserById($id);
        return response()->json([
            'status' => true,
            'user' => $userDTO->toArray(),
        ], 200);
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $userDTO = $this->userService->createUser($request->validated());

            return response()->json([
                'status' => true,
                'user' => $userDTO->toArray(),
                'message' => "Usuário cadastrado com sucesso!",
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(UserRequest $request, int $id): JsonResponse
    {
        try {
            $userDTO = $this->userService->updateUser($id, $request->validated());

            return response()->json([
                'status' => true,
                'user' => $userDTO->toArray(),
                'message' => "Usuário editado com sucesso!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'status' => true,
                'message' => "Usuário apagado com sucesso!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
