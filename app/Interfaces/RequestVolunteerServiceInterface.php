<?php 

namespace App\Interfaces;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;

interface RequestVolunteerServiceInterface
{
    public function create(array $data): RequestVolunteerResource;
}