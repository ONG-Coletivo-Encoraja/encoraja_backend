<?php

namespace App\Interfaces;

use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordCodeRequest;
use App\Http\Requests\Password\ResetPasswordValidateCodeRequest;
use App\Services\ResetPasswordValidateCodeService;
use Illuminate\Http\JsonResponse;

interface RecoverPasswordServiceInterface {
    public function forgotPasswordCode(ForgotPasswordRequest $request): JsonResponse;
    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse;
    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse;
}