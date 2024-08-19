<?php

namespace App\Interfaces;

use App\Http\Resources\Reviews\ReviewsResource;

interface ReviewsServiceInterface
{
    public function create(array $data): ReviewsResource;
}