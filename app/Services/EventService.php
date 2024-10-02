<?php

namespace App\Services;

use App\Http\Resources\Event\EventResource;
use App\Http\Resources\RelatesEvent\RelatesEventResource;
use App\Interfaces\EventServiceInterface;
use App\Models\Event;
use App\Models\RelatesEvent;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService implements EventServiceInterface
{
    public function createAdmin(array $data): EventResource
    {
        DB::beginTransaction();

        try {

            if (in_array($data['status'], ['inactive', 'finished'])) {
                throw new \Exception("O evento não pode ser criado com status 'inativo' ou 'finalizado'", 400);
            }

            $user = User::find($data['owner']);
            if (!$user) {
                throw new \Exception("Usuário responsável não encontrado.", 400);
            }

            if ($user->permissions->type === 'beneficiary') {
                throw new \Exception("Usuário não pode ser cadastrado como responsável.", 400);
            }

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
            throw new \Exception("Evento não cadastrado: " . $e->getMessage(), 400);
        }
    }

    public function createVolunteer(array $data): EventResource
    {
        DB::beginTransaction();

        try {

            if ($data['status'] != 'pending') {
                throw new \Exception("O evento só pode ser cadastrado com o status pendente.", 400);
            }

            if ($data['owner'] != Auth::id()) {
                throw new \Exception("Você só pode criar eventos que são atribuidos a você.", 400);
            }

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
            throw new \Exception("Evento não cadastrado: " . $e->getMessage(), 400);
        }
    }

    public function updateAdmin(int $id, array $data): EventResource
    {
        DB::beginTransaction();

        try {
            $user = User::find($data["owner"]);
            if (!$user) {
                throw new \Exception("Usuário responsável não encontrado.", 400);
            }

            $event = Event::find($id);
            if (!$event) {
                throw new \Exception("Evento não encontrado.", 400);
            }

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
            throw new \Exception("Evento não editado: " . $e->getMessage(), 400);
        }
    }

    public function updateVolunteer(int $id, array $data): EventResource
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);
            if (!$event) {
                throw new \Exception("Evento não encontrado.", 400);
            }

            if ($event->status === 'inactive' || $event->status === 'finished') {
                throw new \Exception("Não é possível editar eventos com status finalizado ou inativo.", 400);
            }

            $relatesEvent = $event->relatesEvents()->first();
            if (!$relatesEvent || $relatesEvent->user_id !== Auth::id()) {
                throw new \Exception("Você não tem permissão para editar este evento.", 403);
            }

            $event->update($data);

            DB::commit();

            return new EventResource($event);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Evento não editado: " . $e->getMessage(), 400);
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);

            $event->relatesEvents()->delete();

            $event->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Evento não deletado: " . $e->getMessage(), 400);
        }
    }

    public function getAll($status = null, $name = null): LengthAwarePaginator
    {
        try {
            $query = Event::query();

            if ($status) {
                $query->where('status', $status);
            }

            if ($name) {
                $query->where(function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%')
                        ->orWhere('description', 'like', '%' . $name . '%');
                });
            }

            $events = $query->paginate(6);

            if ($events->isEmpty()) {
                throw new \Exception("Nenhum evento foi encontrado.", 400);
            }

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
            $event = Event::find($id);

            return new EventResource($event);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar evento: " . $e->getMessage(), 400);
        }
    }

    public function getEventsLoggedUser(): LengthAwarePaginator
    {
        try {
            $loggedId = Auth::user()->id;

            $relates = RelatesEvent::where('user_id', $loggedId)->paginate(5);

            $relates->setCollection(
                $relates->getCollection()->transform(function ($relate) {
                    return new RelatesEventResource($relate);
                })
            );

            return $relates;
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar evento: " . $e->getMessage(), 400);
        }
    }
}
