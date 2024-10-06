<?php

namespace App\Interfaces;

interface ResetPasswordValidateCodeServiceInterface
{
    public function resetPasswordValidateCode($email, $code): array;
}