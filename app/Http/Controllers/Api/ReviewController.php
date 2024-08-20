<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Interfaces\ReviewServiceInterface;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    protected $reviewsService;

    public function __construct(ReviewServiceInterface $reviewsService)
    {
        $this->reviewsService = $reviewsService;
    }

    public function store(ReviewCreateRequest $request): JsonResponse
    {
        try {
            $reviewsService = $this->reviewsService->create($request->validated());

            return response()->json([
                'status' => true,
                'review' => $reviewsService,
                'message' => "Evento avaliado com sucesso!",
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->reviewsService->delete($id);
            
            return response()->json([], 204);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
