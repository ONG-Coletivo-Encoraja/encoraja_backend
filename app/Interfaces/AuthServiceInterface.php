<?php

namespace App\Interfaces;

use App\Dto\AuthDto\AuthDtoResponse;
use App\Dto\UserDto\UserDtoResponse;

interface AuthServiceInterface
{
    public function login(array $credentials): AuthDtoResponse;
    public function logout(): void;
    public function refresh(): AuthDtoResponse;
    public function me(): UserDtoResponse;
}
