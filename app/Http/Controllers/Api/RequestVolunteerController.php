<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestVolunteer\RequestVolunteerCreateRequest;
use App\Interfaces\RequestVolunteerServiceInterface;

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
}
