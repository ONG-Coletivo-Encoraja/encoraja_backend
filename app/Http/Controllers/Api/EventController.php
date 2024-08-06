<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCreateRequest;
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


        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
