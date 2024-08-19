<?php

namespace App\Http\Resources\Reviews;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Event;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Event\EventResource;

class ReviewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $review = Reviews::find($this->id);
        $user = User::find($review->user_id);
        $event = User::find($review->event_id);

        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'observation' => $this->observation,
            'recommendation' => $this->recommendation,
            'user' => $this-> UserResouce($user),
            'event' => $this-> EventResouce($event),
        ];
    }
}
