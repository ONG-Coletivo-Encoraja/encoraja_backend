<?php

namespace App\Interfaces;

use App\Http\Resources\Event\EventResource;

interface EventServiceInterface{
    public function create(array $data): EventResource;
}