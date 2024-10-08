<?php

namespace Tests\Unit;

use App\Http\Resources\User\UserResource;
use App\Interfaces\AuthServiceInterface;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthServiceInterface $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->authService = app(AuthServiceInterface::class);
    }

    // *********** FUNCTIONALITY: Login ***********
    /*
        TDD001 - User logs in with valid data
    */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Permission::factory()->create([
            'user_id' => $user->id
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->authService->login($credentials);

        $this->assertNotNull($response->resource['access_token']);
        $this->assertEquals('bearer', $response->resource['token_type']);
        $this->assertArrayHasKey('expires_in', $response->resource);
        $this->assertEquals($user->id, $response->resource['user']->id);
    }

    /*
        TDD002 - User logs in with invalid data
    */
    public function test_login_failure()
    {
        $credentials = [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Credenciais inválidas, não autenticado!');

        $this->authService->login($credentials);
    }

    /*
        TDD003 - User logs in without providing data
    */
    public function test_login_empty_exception_handling()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Credenciais não fornecidas");

        $this->authService->login([]);
    }

    // *********** FUNCTIONALITY: Logout ***********
    /*
        TDD001 - User logs out with valid token
    */
    public function test_logout_success()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Permission::factory()->create([
            'user_id' => $user->id
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->authService->login($credentials);
        $token = $response->resource['access_token'];

        JWTAuth::setToken($token);

        $this->authService->logout();

        $isValid = JWTAuth::check();

        $this->assertFalse($isValid, "O token ainda é válido, mas não deveria ser.");
    }

    /*
        TDD002 - User logs out with invalid token
    */
    public function test_logout_invalid_token()
    {
        $this->expectException(\Exception::class);

        JWTAuth::setToken('invalid-token');

        $this->authService->logout();
    }

    // *********** FUNCTIONALITY: MyProfile ***********
    /*
        TDD001 - User views their own profile information
    */
    public function test_me_success()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Permission::factory()->create([
            'user_id' => $user->id
        ]);

        $token = JWTAuth::attempt(['email' => $user->email, 'password' => 'password123']);
        JWTAuth::setToken($token);

        $userResource = $this->authService->me();

        $this->assertInstanceOf(UserResource::class, $userResource);

        $this->assertEquals($user->id, $userResource->resource->id);
        $this->assertEquals($user->name, $userResource->resource->name);
        $this->assertEquals($user->email, $userResource->resource->email);
    }

    /*
        TDD002 - User attempts to view their own profile information with an invalid token
    */
    public function test_me_failure()
    {
        JWTAuth::setToken('invalid.token.here');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao trazer informações do usuário logado!");

        $this->authService->me();
    }
}
