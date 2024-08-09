<?php

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Interfaces\AuthServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): AuthResource
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception('Unauthorized', 401);
        }   

        try {
            $user = auth('api')->user();

            event(new UserLoggedIn($user));
            
            $authData = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60, 
                'user' => auth('api')->user(),
            ];
            return new AuthResource($authData);

        }  catch (\Exception $e) {
            throw new \Exception("Usuário não autenticado!" . $e->getMessage(), 400);
        }

    }

    public function logout(): void
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        }  catch (\Exception $e) {
            throw new \Exception("Erro ao inválidar Token!" . $e->getMessage(), 400);
        }
    }

    public function refresh(): AuthResource
    {
        try {
            $token = auth('api')->refresh();

            $authData = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60, 
                'user' => auth('api')->user(),
            ];
            return new AuthResource($authData);
        }  catch (\Exception $e) {
            throw new \Exception("Erro recarregar autenticação!" . $e->getMessage(), 400);
        }
       
    }

    public function me(): UserResource
    {
        try {
            $user = auth('api')->user();

            return new UserResource($user);
        }  catch (\Exception $e) {
            throw new \Exception("Erro ao trazer informações do usuário logado!" . $e->getMessage(), 400);
        }
    }
}
