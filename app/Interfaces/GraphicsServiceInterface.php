<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface GraphicsServiceInterface
{
    public function ethnicityChart(): JsonResponse;
}