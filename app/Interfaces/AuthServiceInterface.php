<?php

namespace App\Interfaces;

use App\Dto\AuthDto\AuthResponse;
use App\Dto\UserDto\UserResponse;

interface AuthServiceInterface
{
    public function login(array $credentials): AuthResponse;
    public function logout(): void;
    public function refresh(): AuthResponse;
    public function me(): UserResponse;
}
