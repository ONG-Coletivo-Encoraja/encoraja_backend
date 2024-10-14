<?php 

namespace App\Interfaces;

use App\Http\Resources\Complaince\ComplainceResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ComplainceServiceInterface {
    public function create(array $data, string $ip_address, string $browser): ComplainceResource;
    public function getAll(): LengthAwarePaginator;
    public function getById(int $id): ComplainceResource;
}