<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\User\UserResource;
use App\Models\RelatesEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $relatesEvents = RelatesEvent::where('event_id', $this->id)->get();
        
        $user = $relatesEvents->isNotEmpty() ? User::find($relatesEvents->first()->user_id) : null;

        return [
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date, 
            'time' => $this->time,
            'modality' => $this->modality,
            'status' => $this->status,
            'type' => $this->type,
            'target_audience' => $this->target_audience,
            'vacancies' => $this->vacancies,
            'social_vacancies' => $this->social_vacancies,
            'regular_vacancies' => $this->regular_vacancies,
            'material' => $this->material,
            'interest_area' => $this->interest_area,
            'price' => $this->price,
            'workload' => $this->workload,
            'user_owner' => new UserResource($user)
        ];
    }
}
