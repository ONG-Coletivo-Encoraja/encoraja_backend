<?php

namespace App\Interfaces;

interface ReportCsvServiceInterface
{
    public function exportCsvUser(): \Illuminate\Http\Response;
    public function exportCsvInscriptionReview(): \Illuminate\Http\Response;
    public function exportCsvEventsReport(): \Illuminate\Http\Response;
    public function exportCsvComplianceReport(): \Illuminate\Http\Response;
}