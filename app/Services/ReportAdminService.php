<?php

namespace App\Services;

use App\Http\Resources\ReportAdmin\ReportAdminResource;
use App\Interfaces\ReportAdminServiceInterface;
use App\Models\Event;
use App\Models\RelatesEvent;
use App\Models\ReportAdmin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportAdminService implements ReportAdminServiceInterface
{
    public function create(array $data): ReportAdminResource
    {
        DB::beginTransaction();

        try {
            $logged = Auth::user();

            $relates_event = RelatesEvent::find($data['relates_event_id']);
            if (!$relates_event) throw new \Exception("Relação de evento não encontrada.", 404);

            if ($logged->id != $relates_event->user_id) throw new \Exception('Apenas o responsável do evento pode enviar o relatório.', 404);

            $event = Event::find($relates_event->event_id);
            if (!$event) throw new \Exception("Evento não encontrado.", 404);
            if ($event->status != 'Finished') throw new \Exception("Evento não finalizado.", 404);

            $existingReport = ReportAdmin::where('relates_event_id', $data['relates_event_id'])->first();
            if ($existingReport) throw new \Exception('Já existe um relatório enviado para este evento.', 404);

            $report = ReportAdmin::create($data);

            DB::commit();
            return new ReportAdminResource($report);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Relatório não enviado: " . $e->getMessage(), 400);
        }
    }

    public function getByEvent(int $eventId): ReportAdminResource
    {
        try {
            $event = Event::findOrFail($eventId);
            if (!$event) throw new \Exception("Evento não encontrado.", 404);

            $relates = RelatesEvent::where('event_id', $event->id)->first();
            if (!$relates) throw new \Exception("Relação de evento não encontrada.", 404);

            $report = ReportAdmin::where('relates_event_id', $relates->id)->first();
            if (!$report) throw new \Exception("Relatório de evento não encontrado.", 404);

            return new ReportAdminResource($report);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar relatórios: " . $e->getMessage(), 400);
        }
    }

    public function getAll($eventName = null): LengthAwarePaginator
    {
        try {
            $query = ReportAdmin::with('relatesEvent.event');

            if ($eventName) {
                $query->whereHas('relatesEvent.event', function ($q) use ($eventName) {
                    $q->where('name', 'like', '%' . $eventName . '%'); 
                });
            }

            $reports = $query->paginate(10);

            $reports->getCollection()->transform(function ($report) {
                return new ReportAdminResource($report);
            });

            return $reports;
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar relatórios: " . $e->getMessage(), 400);
        }
    }


    public function getById(int $id): ReportAdminResource
    {
        try {
            $report = ReportAdmin::find($id);

            return new ReportAdminResource($report);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar relatório: " . $e->getMessage(), 400);
        }
    }

    public function update(int $id, array $data): ReportAdminResource
    {
        DB::beginTransaction();

        try {
            $logged = Auth::user();

            $report = ReportAdmin::findOrFail($id);

            $relates_event = RelatesEvent::findOrFail($report->relates_event_id);

            if ($logged->id != $relates_event->user_id && !$logged->permissions()->where('type', 'administrator')->exists()) {
                DB::rollBack();
                throw new \Exception('Apenas o responsável do evento ou um administrador pode editar o relatório.', 403);
            }

            $report->update($data);

            DB::commit();
            return new ReportAdminResource($report);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Relatório não atualizado: " . $e->getMessage(), 400);
        }
    }
}
