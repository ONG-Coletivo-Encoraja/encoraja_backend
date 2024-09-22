<?php

namespace App\Interfaces;

interface ReportCsvInterface
{
    public function exportCsvUser(): \Illuminate\Http\Response;
}