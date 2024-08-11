<?php

namespace App\Services;

use App\Http\Resources\Inscription\InscriptionResource;
use App\Interfaces\InscriptionServiceInterface;
use App\Models\Event;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscriptionService implements InscriptionServiceInterface
{
    public function createInscription(array $data): InscriptionResource 
    {
        DB::beginTransaction();

        $logged = Auth::user()->id;

        try {
            $event = Event::findOrFail($data['event_id']);

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
}