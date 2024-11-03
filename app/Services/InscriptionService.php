<?php

namespace App\Services;

use App\Http\Resources\Inscription\InscriptionResource;
use App\Interfaces\InscriptionServiceInterface;
use App\Models\Event;
use App\Models\Inscription;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscriptionService implements InscriptionServiceInterface
{
    public function createInscription(array $data): InscriptionResource
    {
        DB::beginTransaction();

        try {
            $logged = Auth::user()->id;

            $event = Event::find($data['event_id']);

            if (!$event) {
                throw new \Exception('Evento não encontrado.', 404);
            }

            if ($event->status != 'active') {
                throw new \Exception('Não é possível se inscriver nesse evento.', 404);
            }

            $existingInscription = Inscription::where('event_id', $event->id)
                ->where('user_id', $logged)
                ->first();

            if ($existingInscription) {
                if ($existingInscription->status === 'rejected') {
                    $existingInscription->delete();
                } else {
                    DB::rollBack();
                    throw new \Exception("Você já está inscrito neste evento.", 400);
                }
            }

            $currentInscriptions = Inscription::where('event_id', $event->id)->count();

            if ($currentInscriptions >= $event->vacancies) {
                DB::rollBack();
                throw new \Exception('Capacidade máxima de vagas atingida para este evento.', 403);
            }

            $data['status'] = ($event->price == 0 || $event->price === null) ? 'approved' : 'pending';
            $data['user_id'] = $logged;

            $inscription = Inscription::create($data);

            DB::commit();

            return new InscriptionResource($inscription);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Inscrição não cadastrada: " . $e->getMessage(), 400);
        }
    }

    public function deleteInscription(int $id): bool
    {
        DB::beginTransaction();

        try {
            $logged = Auth::user()->id;

            $inscription = Inscription::find($id);

            if (!$inscription) {
                DB::rollBack();
                throw new \Exception("Inscrição não encontrada.", 404);
            }

            if ($inscription->user_id != $logged) {
                DB::rollBack();
                throw new \Exception("Você não tem permissão para cancelar inscrição de outros usuário", 400);
            }

            $inscription->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Inscription não deletada: " . $e->getMessage(), 400);
        }
    }

    public function getMyInscription($status = null, $eventName = null): LengthAwarePaginator
    {
        try {
            $logged = Auth::user()->id;
    
            $inscriptions = Inscription::where('user_id', $logged)->with(['event']);
    
            if ($eventName) {
                $inscriptions->whereHas('event', function ($q) use ($eventName) {
                    $q->where('name', 'like', '%' . $eventName . '%');
                });
            }
    
            if ($status) {
                $inscriptions->where('status', $status);
            }
    
            $inscriptions = $inscriptions->paginate(5);
    
            $inscriptions->transform(function ($inscription) {
                return new InscriptionResource($inscription);
            });
    
            return $inscriptions;
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar inscrições: " . $e->getMessage(), 400);
        }
    }
    

    public function getById(int $id): InscriptionResource
    {
        try {
            $inscription = Inscription::find($id);

            if (!$inscription) throw new \Exception("Inscrição não encontra.");

            return new InscriptionResource($inscription);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar inscrição: " . $e->getMessage(), 400);
        }
    }

    public function getInscriptionsByEventId(int $eventId): LengthAwarePaginator
    {
        try {
            $event = Event::find($eventId);
            if (!$event) throw new \Exception("Evento não encontrado.");

            $inscriptions = Inscription::where('event_id', $event->id)->paginate(5);
            if (!$inscriptions) throw new \Exception("Inscrições não encontradas.");

            $inscriptions->transform(function ($inscription) {
                return new InscriptionResource($inscription);
            });

            return $inscriptions;
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar inscrições: " . $e->getMessage(), 400);
        }
    }

    public function getAllInscriptions($status = null, $eventName = null, $userName = null): LengthAwarePaginator
    {
        try {
            $query = Inscription::with(['event', 'user']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($eventName) {
                $query->whereHas('event', function ($q) use ($eventName) {
                    $q->where('name', 'like', '%' . $eventName . '%');
                });
            }

            if ($userName) {
                $query->whereHas('user', function ($q) use ($userName) {
                    $q->where('name', 'like', '%' . $userName . '%');
                });
            }

            $inscriptions = $query->paginate(6);

            if ($inscriptions->isEmpty()) {
                throw new \Exception("Nenhuma inscrição foi encontrada.", 400);
            }

            return $inscriptions;
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar inscrições: " . $e->getMessage(), 400);
        }
    }

    public function update(int $id, array $data): InscriptionResource
    {
        DB::beginTransaction();

        try {
            $inscription = Inscription::find($id);

            if (!$inscription) throw new \Exception("Erro ao encontrar inscrição, inscrição não encontrada.", 404);

            $inscription->update($data);

            DB::commit();

            return new InscriptionResource($inscription);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erro ao encontrar inscrições: " . $e->getMessage(), 400);
        }
    }

    public function present(int $id): InscriptionResource
    {
        try {
            $inscription = Inscription::find($id);

            if (!$inscription) throw new \Exception("Erro ao encontrar inscrição, inscrição não encontrada.", 404);

            $inscription->present = !$inscription->present;
            $inscription->save();

            return new InscriptionResource($inscription);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao mudar presença: " . $e->getMessage(), 400);
        }
    }
}
