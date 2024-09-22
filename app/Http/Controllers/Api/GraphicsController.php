<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\GraphicsServiceInterface;
use Illuminate\Http\JsonResponse;

class GraphicsController extends Controller
{
    protected $graphicsService;

    public function __construct(GraphicsServiceInterface $graphicsService) {
        $this->graphicsService = $graphicsService;
    }
    
    public function ethnicityChart(): JsonResponse
    {
        try {
            return $this->graphicsService->ethnicityChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function presentEventChart(): JsonResponse
    {
        try {
            return $this->graphicsService->presentEventChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function ratingsChart(): JsonResponse
    {
        try {
            return $this->graphicsService->ratingsChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    public function complianceChart(): JsonResponse
    {
        try {
            return $this->graphicsService->complianceChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function ageGroupChart(): JsonResponse
    {
        try {
            return $this->graphicsService->ageGroupChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function participationChart(): JsonResponse
    {
        try {
            return $this->graphicsService->participationChart();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
