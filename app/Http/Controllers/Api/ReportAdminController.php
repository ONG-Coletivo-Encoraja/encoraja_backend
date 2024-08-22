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
}
