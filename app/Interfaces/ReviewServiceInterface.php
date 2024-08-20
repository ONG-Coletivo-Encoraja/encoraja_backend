<?php

namespace App\Interfaces;

use App\Http\Resources\Reviews\ReviewResource;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReviewServiceInterface
{
    public function create(array $data): ReviewResource;
    public function delete(int $id): bool;
    public function getByEvent(int $id): LengthAwarePaginator;
    public function getById(int $id): ReviewResource;
}