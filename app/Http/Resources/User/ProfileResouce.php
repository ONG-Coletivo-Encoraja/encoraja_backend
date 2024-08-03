<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'date_birthday' => $this->date_birthday,
            'race' => $this->race,
            'gender' => $this->gender,
            'availability' => $this->availability,
            'course_experience' => $this->course_experience,
            'how_know' => $this->how_know,
            'expectations' => $this->expectations,
            'address' => [
                'street' => $this->addresses->first()->street,
                'number' => $this->addresses->first()->number,
                'neighbourhood' => $this->addresses->first()->neighbourhood,
                'city' => $this->addresses->first()->city,
                'zip_code' => $this->addresses->first()->zip_code,
            ],
            'permission' => $this->permissions->first()->type,
        ];
    }
}
