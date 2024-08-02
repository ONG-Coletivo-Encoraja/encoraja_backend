<?php

namespace App\Interfaces;

use App\Http\Resources\User\UserResource;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator;
    public function getUserById(int $id): UserResource;
    public function createUser(array $data): UserResource;
    public function updateUser(int $id, array $data): UserResource;
    public function deleteUser(int $id): void;
}