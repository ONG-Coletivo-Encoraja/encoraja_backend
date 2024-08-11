<?php

namespace App\Interfaces;

use App\Http\Resources\Inscription\InscriptionResource;

interface InscriptionServiceInterface 
{
    public function createInscription(array $data): InscriptionResource;
}