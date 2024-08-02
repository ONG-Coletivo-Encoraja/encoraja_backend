<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Dto\AuthDto\AuthDtoResponse;
use App\Dto\UserDto\UserDtoResponse;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): AuthDtoResponse
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception('Unauthorized', 401);
        }

        return new AuthDtoResponse($token, 'bearer', auth('api')->factory()->getTTL() * 180);
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): AuthDtoResponse
    {
        $token = auth('api')->refresh();
        return new AuthDtoResponse($token, 'bearer', auth('api')->factory()->getTTL() * 180);
    }

    public function me(): UserDtoResponse
    {
        $user = auth('api')->user();
        return new UserDtoResponse($user->id, $user->name, $user->email);
    }
}
