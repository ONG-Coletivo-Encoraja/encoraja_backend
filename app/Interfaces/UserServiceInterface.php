<?php

namespace App\Interfaces;

use App\Dto\UserDto\UserResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator;
    public function getUserById(int $id): UserResponse;
    public function createUser(array $data): UserResponse;
    public function updateUser(int $id, array $data): UserResponse;
    public function deleteUser(int $id): void;
}