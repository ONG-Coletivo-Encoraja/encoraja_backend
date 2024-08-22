<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportAdmin\ReportAdminRequest;
use App\Interfaces\ReportAdminServiceInterface;
use Illuminate\Http\JsonResponse;

class ReportAdminController extends Controller
{
    protected $requestReportService;

    public function __construct(ReportAdminServiceInterface $requestReportService)
    {
        $this->requestReportService = $requestReportService;
    }

    public function store(ReportAdminRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $resource = $this->requestReportService->create($validated);
            return response()->json($resource, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getByEvent(int $id): JsonResponse
    {
        try {
            $resource = $this->requestReportService->getByEvent($id);
            return response()->json($resource, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            $resource = $this->requestReportService->getAll();
            return response()->json($resource, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getById(int $id): JsonResponse
    {
        try {
            $resource = $this->requestReportService->getById($id);
            return response()->json($resource, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
