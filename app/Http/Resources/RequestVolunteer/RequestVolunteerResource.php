<?php

namespace App\Http\Resources\RequestVolunteer;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestVolunteerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('request_volunteer_id', $this->id)->first();

        return [
            'id' => $this->id,
            'status' => $this->status,
            'availability' => $this->availability,
            'course_experience' => $this->course_experience,
            'how_know' => $this->how_know,
            'expectations' => $this->expectations,
            'user' => new UserResource($this->user)
        ];
    }
}
