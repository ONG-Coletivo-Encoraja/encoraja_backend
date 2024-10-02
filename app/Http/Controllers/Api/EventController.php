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

    public function storeVolunteer(EventCreateRequest $request): JsonResponse
    {
        try {
            $eventResource = $this->eventService->createVolunteer($request->validated());

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

    public function updateVolunteer(int $id, EventUpdateRequest $request): JsonResponse 
    {
        try {
            $eventResource = $this->eventService->updateVolunteer($id, $request->validated());

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

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eventService->delete($id);

            return response()->json([], 204);

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
            $status = request()->query('status');
            $name = request()->query('name');

            $events = $this->eventService->getAll($status, $name);

            return response()->json([
                'status' => true,
                'events' => $events,
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
            $event = $this->eventService->getById($id);

            return response()->json([
                'status' => true,
                'event' => $event,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getByLogged(): JsonResponse
    {
        try {
            $relates = $this->eventService->getEventsLoggedUser();

            return response()->json([
                'status' => true,
                'relates' => $relates,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
