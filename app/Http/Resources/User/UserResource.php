<?php

namespace App\Http\Resources\User;

use App\Models\User;
use App\Models\RequestVolunteer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->id);
        $permission = $user->permissions;

        $requestVolunteer = RequestVolunteer::find($user->request_volunteer_id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'permission' => $permission->type,
            'phone' => $this->phone,
            'date_birthday' => $this->date_birthday,
            'availability' => optional($requestVolunteer)->availability,
            'course_experience' => optional($requestVolunteer)->course_experience,
            'how_know' => optional($requestVolunteer)->how_know,
            'expectations' => optional($requestVolunteer)->expectations,
        ];
    }
}
