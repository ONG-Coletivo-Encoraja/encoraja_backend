<?php

namespace App\Interfaces;

use App\Http\Resources\Event\EventResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EventServiceInterface{
    public function createAdmin(array $data): EventResource;
    public function updateAdmin(int $id, array $data): EventResource;
    public function delete(int $id): bool;
    public function getAll(): LengthAwarePaginator;
    public function getById(int $id): EventResource;
    public function getEventsLoggedUser(): LengthAwarePaginator;
}