<?php

namespace App\Services;

use App\Http\Resources\Event\EventResource;
use App\Interfaces\EventServiceInterface;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService implements EventServiceInterface 
{
    public function create(array $data): EventResource
    {
        DB::beginTransaction();
        
        try {
            $event = Event::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'date' => $data['date'],
                'time' => $data['time'],
                'modality' => $data['modality'],
                'status' => $data['status'],
                'type' => $data['type'],
                'target_audience' => $data['target_audience'],
                'vacancies' => $data['vacancies'],
                'social_vacancies' => $data['social_vacancies'] ?? null,
                'regular_vacancies' => $data['regular_vacancies'] ?? null,
                'material' => $data['material'] ?? null,
                'interest_area' => $data['interest_area'],
                'price' => $data['price'],
                'workload' => $data['workload'],
            ]);

            $event->relatesEvents()->create([
                'user_id' => $data['owner'],
            ]);

            DB::commit();

            return new EventResource($event);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Usuário não cadastrado: " . $e->getMessage(), 400);
        }
    }
}