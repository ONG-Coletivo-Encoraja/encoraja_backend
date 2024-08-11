<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inscription\InscriptionCreateRequest;
use App\Interfaces\InscriptionServiceInterface;
use Illuminate\Http\JsonResponse;

class InscriptionController extends Controller
{
    protected $inscriptionService;

    public function __construct(InscriptionServiceInterface $inscriptionService)
    {
        $this->inscriptionService = $inscriptionService;
    }

    public function store(InscriptionCreateRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $inscriptionResource = $this->inscriptionService->createInscription($validated);

            return response()->json($inscriptionResource, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy (int $id): JsonResponse
    {
        try {
            $delete = $this->inscriptionService->deleteInscription($id);
            
            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
