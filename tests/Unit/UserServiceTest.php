<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\User;
use App\Services\UserService; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = new UserService();
    }

    public function test_create_user_success()
    {
        $user = User::factory()->make();
        $address = Address::factory()->make();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'cpf' => $user->cpf,
            'date_birthday' => $user->date_birthday,
            'ethnicity' => $user->ethnicity,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'street' => $address->street,
            'number' => $address->number,
            'neighbourhood' => $address->neighbourhood,
            'city' => $address->city,
            'zip_code' => $address->zip_code,
        ];

        $userResource = $this->userService->createUser($data);

        $this->assertInstanceOf(User::class, $userResource->resource);
        $this->assertEquals($user->name, $userResource->resource->name);
        $this->assertDatabaseHas('users', [
            'email' => $userResource->resource->email,
        ]);

        $this->assertDatabaseHas('permissions', [
            'type' => 'beneficiary',
            'user_id' => $userResource->resource->id,
        ]);

        $this->assertDatabaseHas('address', [
            'street' => $address->street,
            'user_id' => $userResource->resource->id,
        ]);
    }

    public function test_create_user_failure()
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Usuário não cadastrado:");

        $this->userService->createUser($data);
    }
}