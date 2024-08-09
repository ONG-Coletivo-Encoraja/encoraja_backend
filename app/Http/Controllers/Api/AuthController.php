<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $authService = $this->authService->login($credentials);

            return response()->json($authService->toArray($request), 200);
        } catch (\Exception $e) {
            return response()->json(['Erro ao autenticar usuário!' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        try {
            $authService = $this->authService->me();
            return response()->json($authService);

        } catch (\Exception $e) {
            return response()->json(['Erro ao trazer dados do usuário logado!' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        try {
            $authService = $this->authService->refresh();
            return response()->json($authService);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            
            return response()->json(['message' => 'Sucesso ao fazer logout!']);
        } catch (\Exception $e) {
            return response()->json(['Erro ao inválidar token!' => $e->getMessage()], $e->getCode());
        }
    }
}
