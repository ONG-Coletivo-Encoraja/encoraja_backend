<?php

namespace App\Dto\AuthDto;

class AuthDtoResponse
{
    public $access_token;
    public $token_type;
    public $expires_in;

    public function __construct($access_token, $token_type, $expires_in)
    {
        $this->access_token = $access_token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
    }

    public function toArray()
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
        ];
    }
}
