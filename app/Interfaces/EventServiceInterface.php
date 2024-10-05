<?php

namespace App\Interfaces;

use App\Http\Resources\Event\EventResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EventServiceInterface{
    public function createAdmin(array $data): EventResource;
    public function updateAdmin(int $id, array $data): EventResource;
    public function createVolunteer(array $data): EventResource;
    public function updateVolunteer(int $id, array $data): EventResource;
    public function delete(int $id): bool;
    public function getAll($status = null, $name = null): LengthAwarePaginator;
    public function getById(int $id): EventResource;
    public function getEventsLoggedUser(): LengthAwarePaginator;
}