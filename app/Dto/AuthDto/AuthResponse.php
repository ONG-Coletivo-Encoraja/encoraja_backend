<?php

namespace App\Dto\AuthDto;

use App\Dto\UserDto\UserResponse;

class AuthResponse
{
    public $access_token;
    public $token_type;
    public $expires_in;
    public UserResponse $user;

    public function __construct($access_token, $token_type, $expires_in, $user)
    {
        $this->access_token = $access_token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
        $this->user = $user;
    }

    public function toArray()
    {
        return [
            'token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'user' => $this->user
        ];
    }
}
