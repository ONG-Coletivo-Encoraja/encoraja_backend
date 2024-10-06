<?php

namespace App\Services;

use App\Interfaces\ResetPasswordValidateCodeServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordValidateCodeService implements ResetPasswordValidateCodeServiceInterface {

    public function resetPasswordValidateCode($email, $code): array
    {
        $passwordResetTokens = DB::table('password_reset_tokens')->where('email', $email)->first();

        if(!$passwordResetTokens){
            return [
                'status' => false,
                'message' => 'Código não encontrado!',
            ];
        }

        if(!Hash::check($code, $passwordResetTokens->token)){
            return [
                'status' => false,
                'message' => 'Código inválido!',
            ];
        }

        $differenceInMinutes = Carbon::parse($passwordResetTokens->created_at)->diffInMinutes(Carbon::now());

        if($differenceInMinutes > 60){
            return [
                'status' => false,
                'message' => 'Código expirado!',
            ];
        }

        return [
            'status' => true,
            'message' => 'Código válido!',
        ];
    }
}