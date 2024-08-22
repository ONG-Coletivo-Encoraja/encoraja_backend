<?php

namespace App\Interfaces;

use App\Http\Resources\ReportAdmin\ReportAdminResource;

interface ReportAdminServiceInterface
{
    public function create(array $data): ReportAdminResource;
}