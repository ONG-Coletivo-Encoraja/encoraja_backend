<?php

namespace App\Services;

use App\Interfaces\ReviewsServiceInterface;

class ReviewService implements ReviewsServiceInterface {
    public function create(array $data): ReviewsResource
    {
        try {

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar cria uma avaliação." . $e->getMessage(), 400);
        }
    }
}
