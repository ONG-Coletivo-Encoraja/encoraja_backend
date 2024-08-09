<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Interfaces\EventServiceInterface;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventServiceInterface $eventService) {
        $this->eventService = $eventService;
    }

    public function store(EventCreateRequest $request): JsonResponse
    {
        try {
            $eventResource = $this->eventService->createAdmin($request->validated());

            return response()->json([
                'status' => true,
                'event' => $eventResource,
                'message' => "Evento cadastrado com sucesso!",
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(int $id, EventUpdateRequest $request): JsonResponse 
    {
        try {
            $eventResource = $this->eventService->updateAdmin($id, $request->validated());

            return response()->json([
                'status' => true,
                'event' => $eventResource,
                'message' => "Evento atualizado com sucesso!",
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
