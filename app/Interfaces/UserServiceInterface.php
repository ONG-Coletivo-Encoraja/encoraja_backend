<?php

namespace App\Interfaces;

use App\Http\Resources\User\ProfileResouce;
use App\Http\Resources\User\UserResource;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator;
    public function getUserById(int $id): UserResource;
    public function createUser(array $data): UserResource;
    public function updateLoggedUser(array $data): ProfileResouce;
    public function updatePermissionUser(int $id, array $data);
    public function deleteUser(int $id): void;
    public function me(): ProfileResouce;
}