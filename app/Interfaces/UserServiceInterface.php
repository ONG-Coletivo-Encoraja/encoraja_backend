<?php

namespace App\Interfaces;

use App\Http\Resources\User\ProfileResouce;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers($permission = null, $name = null): LengthAwarePaginator;
    public function getUserById(int $id): UserResource;
    public function createUser(array $data): UserResource;
    public function updateLoggedUser(array $data): ProfileResouce;
    public function updatePermissionUser(int $id, array $data);
    public function deleteUser(): void;
    public function me(): ProfileResouce;
    public function getAllVolunteer(): AnonymousResourceCollection;
}