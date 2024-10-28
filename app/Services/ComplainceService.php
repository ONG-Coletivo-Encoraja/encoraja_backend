<?php

namespace App\Services;

use App\Http\Resources\Complaince\ComplainceResource;
use App\Interfaces\ComplainceServiceInterface;
use App\Models\Complaince;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ComplainceService implements ComplainceServiceInterface {
    public function create(array $data, string $ip_address, string $browser): ComplainceResource
    {
        DB::beginTransaction();
        try {
            $complaince = Complaince::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'],
                'relation' => $data['relation'],
                'motivation' => $data['motivation'],
                'browser' => $browser,
                'ip_address' => $ip_address,
            ]);

            DB::commit();

            return new ComplainceResource($complaince);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Denúncia não cadastrada: " . $e->getMessage(), 400);
        }
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            $complainces = Complaince::paginate(6);
    
            if ($complainces->isEmpty()) {
                throw new \Exception("Denúncias não encontradas.");
            }
    
            return $complainces;
    
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar denúncias: " . $e->getMessage(), 400);
        }
    }
    

    public function getById(int $id): ComplainceResource
    {
        try {
            $complaince = Complaince::find($id);

            if (!$complaince) throw new \Exception("Denúncia não encontrada.");
 
            return new ComplainceResource($complaince);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar denúncia: " . $e->getMessage(), 400);
        }
    }
}