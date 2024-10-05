<?php

namespace App\Services;

use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordCodeRequest;
use App\Http\Requests\Password\ResetPasswordValidateCodeRequest;
use App\Interfaces\RecoverPasswordServiceInterface;
use App\Mail\SendEmailForgotPasswordCode;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RecoverPasswordService implements RecoverPasswordServiceInterface {
    
    public function forgotPasswordCode(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'E-mail não encontrado.'
            ], 400);
        }

        try {

            $userPasswordResets = DB::table('password_reset_tokens')->where([
                ['email', $request->email]
            ]);

            if ($userPasswordResets) $userPasswordResets->delete();

            $code = mt_rand(100000, 999999);

            $token = Hash::make($code);

            $userNewPasswordResets = DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            if ($userNewPasswordResets) {
                $currentDate = Carbon::now();

                $oneHourLater = $currentDate->addHour();

                $formattedTime = $oneHourLater->format('H:i');
                $formattedDate = $oneHourLater->format('d/m/Y');

                Mail::to($user->email)->send(new SendEmailForgotPasswordCode($user, $code, $formattedDate, $formattedTime));
            }

            return response()->json([
                'status' => true,
                'message' => 'Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuprar a senha.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar senha. Tente mais tarde!'
            ], 400);
        }
    }

    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        try {
            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            if (!$validationResult['status']) {
                return response()->json([
                    'status' => false, 
                    'message' => $validationResult['message']
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Código de recuperação de senha válido.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao validar código.'
            ], 400);
        }
    }

    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {

        try{
            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            if(!$validationResult['status']){
                return response()->json([
                    'status' => false,
                    'message' => $validationResult['message'],
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado!',
                ], 400);

            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $userPasswordResets = DB::table('password_reset_tokens')->where('email', $request->email);

            if($userPasswordResets){
                $userPasswordResets->delete();
            }

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Senha atualizada com sucesso!',
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Senha não atualizada!',
            ], 400);
        }
    }
}