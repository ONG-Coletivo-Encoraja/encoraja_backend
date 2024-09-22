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

    public function presentEvent(): JsonResponse
    {
        try {
            return $this->graphicsService->presentEvent();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
