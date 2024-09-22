<?php

namespace App\Interfaces;

interface ReportCsvInterface
{
    public function exportCsvUser(): \Illuminate\Http\Response;
    public function exportCsvInscriptionReview(): \Illuminate\Http\Response;
    public function exportCsvEventsReport(): \Illuminate\Http\Response;
}