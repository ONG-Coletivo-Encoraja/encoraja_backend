<?php

namespace Tests\Unit;

use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordCodeRequest;
use App\Http\Requests\Password\ResetPasswordValidateCodeRequest;
use App\Interfaces\RecoverPasswordServiceInterface;
use App\Mail\SendEmailForgotPasswordCode;
use App\Models\User;
use App\Services\ResetPasswordValidateCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RecoverPasswordServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecoverPasswordServiceInterface $recoverPasswordService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->recoverPasswordService = app(RecoverPasswordServiceInterface::class);
    }

    // *********** FUNCTIONALITY: Forgot password and recover it ***********
    /*
        TDD001 - Forgot password code not found
    */
    public function test_forgot_password_code_user_not_found()
    {
        $request = new ForgotPasswordRequest(['email' => 'nonexistent@example.com']);

        $response = $this->recoverPasswordService->forgotPasswordCode($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => false, 'message' => 'E-mail não encontrado.']),
            $response->getContent()
        );
    }

    /*
        TDD002 - Forgot password code email sent
    */
    public function test_forgot_password_code_email_sent()
    {
        Mail::fake();
        User::factory()->create(['email' => 'user@example.com']);
        $request = new ForgotPasswordRequest(['email' => 'user@example.com']);

        $response = $this->recoverPasswordService->forgotPasswordCode($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => true, 'message' => 'Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuprar a senha.']),
            $response->getContent()
        );
        Mail::assertSent(SendEmailForgotPasswordCode::class);
    }

    /*
        TDD003 - Reset password validate code not found
    */
    public function test_reset_password_validate_code_user_not_found()
    {
        $request = new ResetPasswordValidateCodeRequest(['email' => 'nonexistent@example.com', 'code' => '123456']);

        /** @var \Mockery\MockInterface|\App\Services\ResetPasswordValidateCodeService $mockService */
        $mockService = \Mockery::mock(ResetPasswordValidateCodeService::class);
        $mockService->shouldReceive('resetPasswordValidateCode')->andReturn(['status' => false, 'message' => 'Código inválido.']);

        $response = $this->recoverPasswordService->resetPasswordValidateCode($request, $mockService);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => false, 'message' => 'Código inválido.']),
            $response->getContent()
        );
    }

    /*
        TDD004 - Reset password code success
    */
    public function test_reset_password_code_successful()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $request = new ResetPasswordCodeRequest(['email' => 'user@example.com', 'code' => '123456', 'password' => 'newpassword']);

        /** @var \Mockery\MockInterface|\App\Services\ResetPasswordValidateCodeService $mockService */
        $mockService = \Mockery::mock(ResetPasswordValidateCodeService::class);
        $mockService->shouldReceive('resetPasswordValidateCode')->andReturn(['status' => true]);

        $response = $this->recoverPasswordService->resetPasswordCode($request, $mockService);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    /*
        TDD005 - Reset password code not found
    */
    public function test_reset_password_code_user_not_found()
    {
        $request = new ResetPasswordCodeRequest(['email' => 'nonexistent@example.com', 'code' => '123456', 'password' => 'newpassword']);

        /** @var \Mockery\MockInterface|\App\Services\ResetPasswordValidateCodeService $mockService */
        $mockService = \Mockery::mock(ResetPasswordValidateCodeService::class);

        $mockService->shouldReceive('resetPasswordValidateCode')->andReturn(['status' => true]);

        $response = $this->recoverPasswordService->resetPasswordCode($request, $mockService);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => false, 'message' => 'Usuário não encontrado!']),
            $response->getContent()
        );
    }

    /*
        TDD006 - Reset password code error
    */
    public function test_reset_password_code_error_handling()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $request = new ResetPasswordCodeRequest(['email' => 'user@example.com', 'code' => '123456', 'password' => 'newpassword']);

        /** @var \Mockery\MockInterface|\App\Services\ResetPasswordValidateCodeService $mockService */
        $mockService = \Mockery::mock(ResetPasswordValidateCodeService::class);
        $mockService->shouldReceive('resetPasswordValidateCode')->andThrow(new \Exception('Error'));

        $response = $this->recoverPasswordService->resetPasswordCode($request, $mockService);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => false, 'message' => 'Senha não atualizada!']),
            $response->getContent()
        );
    }
}
