<?php

namespace App\Interfaces;

use App\Http\Resources\Event\EventResource;

interface EventServiceInterface{
    public function createAdmin(array $data): EventResource;
    public function updateAdmin(int $id, array $data): EventResource;
    public function delete(int $id): bool;
}