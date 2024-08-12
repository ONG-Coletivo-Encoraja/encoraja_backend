<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestVolunteer\RequestVolunteerCreateRequest;
use App\Interfaces\RequestVolunteerServiceInterface;
use Illuminate\Http\JsonResponse;

class RequestVolunteerController extends Controller
{
    protected $requestVolunteerService;

    public function __construct(RequestVolunteerServiceInterface $requestVolunteerService)
    {
        $this->requestVolunteerService = $requestVolunteerService;
    }

    public function store(RequestVolunteerCreateRequest $request)
    {
        $validated = $request->validated();

        try {
            $resource = $this->requestVolunteerService->create($validated);
            return response()->json($resource, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAllRequests() : JsonResponse
    {
        try {
            $requests = $this->requestVolunteerService->listAllRequest();
        
            return response()->json([
                'status' => true,
                'requests' => $requests,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
