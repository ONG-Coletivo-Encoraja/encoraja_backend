<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface GraphicsServiceInterface
{
    public function ethnicityChart(): JsonResponse;
    public function presentEventChart(): JsonResponse;
    public function ratingsChart(): JsonResponse;
    public function complianceChart(): JsonResponse;
    public function ageGroupChart(): JsonResponse;
    public function participationChart(): JsonResponse;
}