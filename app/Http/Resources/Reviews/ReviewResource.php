<?php

namespace App\Http\Resources\Reviews;

use App\Http\Resources\Event\EventResource;
use App\Http\Resources\User\UserResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->user_id);

        $event = Event::find($this->event_id);        
        
        return [
            'id' => $this->id,
            'user' => new UserResource($user),
            'event' => new EventResource($event),
            'rating' => $this->rating,
            'observation' => $this->observation,
            'recommendation' => $this->recommendation,
            'feel_welcomed' => $this->feel_welcomed
        ];
    }
}
