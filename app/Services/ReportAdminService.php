<?php

namespace App\Services;

use App\Http\Resources\ReportAdmin\ReportAdminResource;
use App\Interfaces\ReportAdminServiceInterface;
use App\Models\RelatesEvent;
use App\Models\ReportAdmin;
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

            if($logged->id != $relates_event->user_id) {
                DB::rollBack();
                throw new \Exception('Apenas o responsável do evento pode enviar o relatório.', 403);
            }

            $report = ReportAdmin::create($data);

            DB::commit();
            return new ReportAdminResource($report);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Relatório não enviado: " . $e->getMessage(), 400);
        }
    }
}