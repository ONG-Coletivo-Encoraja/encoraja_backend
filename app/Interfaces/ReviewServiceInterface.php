<?php

namespace App\Interfaces;

use App\Http\Resources\Reviews\ReviewResource;

interface ReviewServiceInterface
{
    public function create(array $data): ReviewResource;
}