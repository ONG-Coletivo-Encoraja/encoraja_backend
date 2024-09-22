<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ReportCsvInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response; 

class ReportsCsvController extends Controller
{
    protected $reportCsvService;

    public function __construct(ReportCsvInterface $reportCsvService)
    {
        $this->reportCsvService = $reportCsvService;
    }

    public function exportCsvUser(): Response | JsonResponse
    {
        try {
            return $this->reportCsvService->exportCsvUser();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exportCsvInscriptionReview(): Response | JsonResponse
    {
        try {
            return $this->reportCsvService->exportCsvInscriptionReview();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
