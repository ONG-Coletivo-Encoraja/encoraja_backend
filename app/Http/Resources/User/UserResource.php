<?php

namespace App\Http\Resources\User;

use App\Models\User;
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
        $permission = $user->permissions->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'permission' => $permission ? $permission->type : null,
        ];
    }
}
