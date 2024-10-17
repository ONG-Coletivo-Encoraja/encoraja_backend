<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\User\UserResource;
use App\Models\Inscription;
use App\Models\RelatesEvent;
use App\Models\ReportAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $relatesEvents = RelatesEvent::where('event_id', $this->id)->get();
        
        $user = $relatesEvents->isNotEmpty() ? User::find($relatesEvents->first()->user_id) : null;

        $reportExists = $relatesEvents->isNotEmpty() ? 
        ReportAdmin::where('relates_event_id', $relatesEvents->first()->id)->exists() : false;

        $isUserSubscribed = false;

        $isUserSubscribed = Inscription::where('event_id', $this->id)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'approved')
            ->exists();

        return [
            'id' => $this->id,
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
            'user_owner' => new UserResource($user),
            'report_exists' => $reportExists,
            'subscribed' => $isUserSubscribed,
        ];
    }
}
