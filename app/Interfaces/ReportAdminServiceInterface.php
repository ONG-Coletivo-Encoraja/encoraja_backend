<?php

namespace App\Interfaces;

use App\Http\Resources\ReportAdmin\ReportAdminResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportAdminServiceInterface
{
    public function create(array $data): ReportAdminResource;
    public function getByEvent(int $eventId): ReportAdminResource;
    public function getAll(): LengthAwarePaginator;
    public function getById(int $id): ReportAdminResource;
    public function update(int $id, array $data): ReportAdminResource;
}