<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaince\ComplainceCreateRequest;
use App\Interfaces\ComplainceServiceInterface;
use Illuminate\Http\JsonResponse;

class ComplainceController extends Controller
{
    protected $complainceService;

    public function __construct(ComplainceServiceInterface $complainceService) {
        $this->complainceService = $complainceService;
    }

    public function store(ComplainceCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $ipAddress = $request->ip();
            
            $userAgent = $request->header('User-Agent'); 

            $complainceResource = $this->complainceService->create($validated, $ipAddress, $userAgent);

            return response()->json([
                'status' => true,
                'event' => $complainceResource,
                'message' => "Resposta enviada com sucesso!",
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    } 

    public function getAll(): JsonResponse
    {
        try {
            $complainceResource = $this->complainceService->getAll();

            return response()->json([
                'status' => true,
                'compliances' => $complainceResource,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    } 

    public function getById(int $id): JsonResponse
    {
        try {
            $complainceResource = $this->complainceService->getById($id);

            return response()->json([
                'status' => true,
                'event' => $complainceResource,
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    } 

}
