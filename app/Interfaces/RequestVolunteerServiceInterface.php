<?php 

namespace App\Interfaces;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RequestVolunteerServiceInterface
{
    public function create(array $data): RequestVolunteerResource;
    public function listAllRequest(): LengthAwarePaginator;
    public function update(array $data): RequestVolunteerResource;
    public function updateStatus(int $id, array $data): RequestVolunteerResource;
}