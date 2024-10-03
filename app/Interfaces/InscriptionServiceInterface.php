<?php

namespace App\Interfaces;

use App\Http\Resources\Inscription\InscriptionResource;
use Illuminate\Pagination\LengthAwarePaginator;

interface InscriptionServiceInterface 
{
    public function createInscription(array $data): InscriptionResource;
    public function deleteInscription(int $id): bool;
    public function getMyInscription(): LengthAwarePaginator;
    public function getById(int $id): InscriptionResource;
    public function getInscriptionsByEventId(int $eventId): LengthAwarePaginator;
    public function getAllInscriptions($status = null, $eventName = null, $userName = null): LengthAwarePaginator;
    public function update(int $id, array $data): InscriptionResource;
}