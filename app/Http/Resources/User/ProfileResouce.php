<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProfileResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        $requestVolunteer = $user->requestVolunteer()->first();
        $address = $user->addresses()->first();
        $permission = $user->permissions()->first();

        return [
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'date_birthday' => $this->date_birthday,
            'ethnicity' => $this->ethnicity,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'availability' => optional($requestVolunteer)->availability,
            'course_experience' => optional($requestVolunteer)->course_experience,
            'how_know' => optional($requestVolunteer)->how_know,
            'expectations' => optional($requestVolunteer)->expectations,
            'address' => $address ? [
                'street' => $address->street,
                'number' => $address->number,
                'neighbourhood' => $address->neighbourhood,
                'city' => $address->city,
                'zip_code' => $address->zip_code,
            ] : null,
            'permission' => $permission->type,
        ];
    }
}
