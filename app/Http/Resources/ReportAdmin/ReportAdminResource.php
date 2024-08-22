<?php

namespace App\Http\Resources\ReportAdmin;

use App\Http\Resources\RelatesEvent\RelatesEventResource;
use App\Models\RelatesEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $relates = RelatesEvent::find($this->relates_event_id);

        return [
            'qtt_person'=> $this->qtt_person,
            'description' => $this->description,
            'results' => $this->results,
            'observation' => $this->observation,
            'relates_event'=> new RelatesEventResource($relates)
        ];
    }
}
