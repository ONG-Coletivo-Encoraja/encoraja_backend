<?php

namespace App\Dto\UserDto;

use App\Models\User;

class UserResponse
{
    public $id;
    public $name;
    public $email;
    public $permission;

    public function __construct($id, $name, $email)
    {
        $user = User::find($id);
        $permission = $user->permissions->first();

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->permission = $permission->type;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'permission' => $this->permission
        ];
    }
}
