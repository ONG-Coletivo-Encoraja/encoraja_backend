<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAdminUpdateRequest;
use App\Http\Requests\User\UserCreateRequest;
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
        try {
            $users = $this->userService->getAllUsers();
            
            return response()->json([
                'status' => true,
                'users' => $users,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400); 
        } 
    }

    public function show(int $id): JsonResponse
    {
        try {
            $userResource = $this->userService->getUserById($id);

            return response()->json([
                'status' => true,
                'user' => $userResource,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400); 
        }
        
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            $userResource = $this->userService->createUser($request->validated());

            return response()->json([
                'status' => true,
                'user' => $userResource,
                'message' => "Usuário cadastrado com sucesso!",
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(int $id, UserAdminUpdateRequest $request): JsonResponse
    {
        try {
            $userResource = $this->userService->updatePermissionUser($id, $request->validated());

            return response()->json([
                'status' => true,
                'user' => $userResource,
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
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
