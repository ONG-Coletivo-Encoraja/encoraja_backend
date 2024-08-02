<?php

namespace App\Interfaces;

use App\Dto\UserDto\UserDtoResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator;
    public function getUserById(int $id): UserDtoResponse;
    public function createUser(array $data): UserDtoResponse;
    public function updateUser(int $id, array $data): UserDtoResponse;
    public function deleteUser(int $id): void;
}