<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordCodeRequest;
use App\Http\Requests\Password\ResetPasswordValidateCodeRequest;
use App\Interfaces\RecoverPasswordServiceInterface;
use App\Services\ResetPasswordValidateCodeService;
use Exception;
use Illuminate\Http\JsonResponse;

class RecoverPasswordCodeController extends Controller
{
    protected $pass;

    public function __construct(RecoverPasswordServiceInterface $pass) {
        $this->pass = $pass;
    }
    
    public function forgotPasswordCode(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            return $this->pass->forgotPasswordCode($request);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        try {
            return $this->pass->resetPasswordValidateCode($request, $resetPasswordValidateCode);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        try {
            return $this->pass->resetPasswordCode($request, $resetPasswordValidateCode);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
