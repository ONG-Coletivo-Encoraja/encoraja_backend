<?php

namespace App\Services;

use App\Http\Resources\Inscription\InscriptionResource;
use App\Interfaces\InscriptionServiceInterface;
use App\Models\Event;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;

class InscriptionService implements InscriptionServiceInterface
{
    public function createInscription(array $data): InscriptionResource 
    {
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($data['event_id']);

            $existingInscription = Inscription::where('event_id', $event->id)
                ->where('user_id', $data['user_id'])
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

           
            $inscription = Inscription::create($data);

            DB::commit();

            return new InscriptionResource($inscription);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Inscrição não cadastrada: " . $e->getMessage(), 400);
        }
    }
}