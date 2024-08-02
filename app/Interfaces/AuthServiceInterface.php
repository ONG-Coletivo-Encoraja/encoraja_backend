<?php

namespace App\Interfaces;

use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\User\UserResource;

interface AuthServiceInterface
{
    public function login(array $credentials): AuthResource;
    public function logout(): void;
    public function refresh(): AuthResource;
    public function me(): UserResource;
}
