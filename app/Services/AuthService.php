<?php

namespace App\Services;

use App\Dto\AuthDto\AuthResponse;
use App\Interfaces\AuthServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Dto\UserDto\UserResponse;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): AuthResponse
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception('Unauthorized', 401);
        }   

        return new AuthResponse($token, 'bearer', auth('api')->factory()->getTTL() * 180, $this->me());
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): AuthResponse
    {
        $token = auth('api')->refresh();
        return new AuthResponse($token, 'bearer', auth('api')->factory()->getTTL() * 180, $this->me());
    }

    public function me(): UserResponse
    {
        $user = auth('api')->user();

        return new UserResponse($user->id, $user->name, $user->email, );
    }
}
