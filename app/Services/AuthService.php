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

        $user = auth('api')->user();

        event(new UserLoggedIn($user));
        
        $authData = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, 
            'user' => auth('api')->user(),
        ];

        
        return new AuthResource($authData);
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): AuthResource
    {
        $token = auth('api')->refresh();

        $authData = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, 
            'user' => auth('api')->user(),
        ];
        return new AuthResource($authData);
    }

    public function me(): UserResource
    {
        $user = auth('api')->user();

        return new UserResource($user);
    }
}
