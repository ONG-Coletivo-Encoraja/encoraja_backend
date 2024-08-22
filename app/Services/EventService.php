<?php

namespace App\Services;

use App\Http\Resources\Event\EventResource;
use App\Http\Resources\RelatesEvent\RelatesEventResource;
use App\Interfaces\EventServiceInterface;
use App\Models\Event;
use App\Models\RelatesEvent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService implements EventServiceInterface 
{
    public function createAdmin(array $data): EventResource
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

    public function updateAdmin(int $id, array $data): EventResource
    {
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);
            $event->update($data);


            if (isset($data['owner'])) {
                $relatesEvent = $event->relatesEvents()->first();
                if ($relatesEvent) {
                    $relatesEvent->update([
                        'user_id' => $data['owner'],
                    ]);
                }
            }

            DB::commit();
            
            return new EventResource($event);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Evento não editado!", 400);
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);

            $event->relatesEvents()->delete();

            $event->delete();

            DB::commit();
            
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Evento não deletado: " . $e->getMessage(), 400);
        }
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            $events = Event::paginate(5);

            $eventResources = $events->getCollection()->transform(function ($event) {
                return new EventResource($event);
            });

            return new LengthAwarePaginator($eventResources, $events->total(), $events->perPage(), $events->currentPage(), [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $events->getPageName(),
            ]);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar eventos: " . $e->getMessage(), 400);
        }
    }

    public function getById(int $id): EventResource
    {
        try {
            $event = Event::findOrFail($id);

            return new EventResource($event);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar evento: " . $e->getMessage(), 400);
        }
    }

    public function getEventsLoggedUser(): LengthAwarePaginator
    {
        try {
            $loggedId = Auth::user()->id;

            $relates = RelatesEvent::where('user_id', $loggedId)->paginate(10);

            $relates->getCollection()->transform(function ($relate) {
                return new RelatesEventResource($relate);
            });
    
            return $relates;

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar evento: " . $e->getMessage(), 400);
        }
    }
}