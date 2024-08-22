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
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReportAdminService implements ReportAdminServiceInterface
{
    public function create(array $data): ReportAdminResource
    {
        DB::beginTransaction();

        try {
            $logged = Auth::user();

            $relates_event = RelatesEvent::find($data['relates_event_id']);

            if($logged->id != $relates_event->user_id) {
                DB::rollBack();
                throw new \Exception('Apenas o responsável do evento pode enviar o relatório.', 403);
            }
            $existingReport = ReportAdmin::where('relates_event_id', $data['relates_event_id'])->first();

            if ($existingReport) {
                DB::rollBack();
                throw new \Exception('Já existe um relatório enviado para este evento.', 409);
            }
    
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

            $relates = RelatesEvent::where('event_id', $event->id)->first();
           
            $report = ReportAdmin::where('relates_event_id', $relates->id)->first();

            return new ReportAdminResource($report);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar relatórios: " . $e->getMessage(), 400);
        }
    }

    public function getAll(): LengthAwarePaginator 
    {
        try {
            $reports = ReportAdmin::paginate(5);

            $reports->getCollection()->transform(function ($report) {
                return new ReportAdminResource($report);
            });

            return $reports;

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar inscrição: " . $e->getMessage(), 400);
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
}