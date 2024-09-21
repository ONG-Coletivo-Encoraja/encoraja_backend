<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inscription\InscriptionCreateRequest;
use App\Http\Requests\Inscription\InscriptionUpdateRequest;
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

    public function getMyInscriptions(): JsonResponse
    {
        try {
            $inscription = $this->inscriptionService->getMyInscription();

            return response()->json([
                'status' => true,
                'inscriptions' => $inscription,
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
            $inscription = $this->inscriptionService->getById($id);

            return response()->json([
                'status' => true,
                'inscription' => $inscription,
            ], 200);

        }  catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getByEventId(int $id): JsonResponse
    {
        try {
            $inscriptions = $this->inscriptionService->getInscriptionsByEventId($id);

            return response()->json([
                'status' => true,
                'inscriptions' => $inscriptions,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(int $id, InscriptionUpdateRequest $request) 
    {
        try {
            $validated = $request->validated(); 
            $inscription = $this->inscriptionService->update($id, $validated);

            return response()->json([
                'status' => true,
                'inscription' => $inscription,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
