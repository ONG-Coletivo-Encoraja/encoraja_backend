<?php

namespace Tests\Unit;

use App\Http\Resources\User\ProfileResouce;
use App\Http\Resources\User\UserResource;
use App\Interfaces\AuthServiceInterface;
use App\Models\Address;
use App\Models\User;
use App\Interfaces\UserServiceInterface;
use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserServiceInterface $userService;
    protected AuthServiceInterface $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = app(UserServiceInterface::class);
        $this->authService = app(AuthServiceInterface::class);
    }

    private function seed_users_and_permissions()
    {
        $userAdmin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com']);
        $userRegular = User::factory()->create(['name' => 'Regular User', 'email' => 'user@example.com']);

        $userAdmin->permissions()->create(['type' => 'administrator']);
        $userRegular->permissions()->create(['type' => 'administrator']);
    }

    // *********** FUNCTIONALITY: Update logged user ***********
    /*
       TDD001 - update logged user info 
    */
    public function test_update_logged_user_success()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        Permission::factory()->create(['type' => 'administrator','user_id' => $user->id]);
        Address::factory()->create(['user_id' => $user->id]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $this->authService->login($credentials);


        $data = ['name' => 'Updated Name', 'email' => 'updated@example.com'];

        $profileResource = $this->userService->updateLoggedUser($data);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $this->assertEquals($user->id, $profileResource->id);
    }

    // *********** FUNCTIONALITY: Change permission ***********
    /*
       TDD001 - Change user permission 
    */
    public function test_update_permission_user_success()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $permission = Permission::factory()->create(['type' => 'administrator','user_id' => $user->id]);

        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'type' => 'administrator']);

        $data = ['type' => 'volunteer'];

        $userResource = $this->userService->updatePermissionUser($user->id, $data);

        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'type' => 'volunteer']);
        $this->assertEquals($user->id, $userResource->id);
    }

    /*
       TDD002 - Change user permission with invalid id
    */
    public function test_update_permission_user_not_found()
    {
        $data = ['type' => 'volunteer'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Permissão de usuário não alterada!");

        $this->userService->updatePermissionUser(999, $data);
    }

    // *********** FUNCTIONALITY: Get My informations ***********
    /*
       TDD001 - Get informations by logged user
    */
    public function test_me_returns_users_profile()
    {

        $user = User::factory()->create(['password' => Hash::make('password123')]);
        Permission::factory()->create(['user_id' => $user->id]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $this->authService->login($credentials);

        $response = $this->userService->me();
        $this->assertInstanceOf(ProfileResouce::class, $response);
    }

    // *********** FUNCTIONALITY: Delete my account ***********
    /*
       TDD001 - Delete my own account
    */
    public function test_delete_user_successfully()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        Permission::factory()->create(['user_id' => $user->id]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $this->authService->login($credentials);

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->userService->deleteUser();

        $remainingUser = User::find($user->id);
        $this->assertEquals(null, $remainingUser);
    }

    // *********** FUNCTIONALITY: Get user by id ***********
    /*
       TDD001 - Get user by id
    */
    public function test_ge_user_by_id_successfully()
    {
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);
        $user->permissions()->create(['type' => 'administrator']);

        $result = $this->userService->getUserById($user->id);

        $this->assertInstanceOf(UserResource::class, $result);
        $this->assertEquals($user->id, $result->resource->id);
        $this->assertEquals($user->name, $result->resource->name);
        $this->assertEquals($user->email, $result->resource->email);
    }

    /*
       TDD002 - Get user by id with a wrong id
    */
    public function test_get_user_by_id_throws_exception_for_non_existent_user()
    {
        $nonExistentId = 999;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar usuário.");

        $this->userService->getUserById($nonExistentId);
    }

    // *********** FUNCTIONALITY: Get all users ***********
    /*
       TDD001 - Get all user without filters
    */
    public function test_get_all_users_without_filters()
    {
        $this->seed_users_and_permissions();

        $result = $this->userService->getAllUsers();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(2, $result->items());
        $this->assertEquals(2, $result->total());
    }

    /*
       TDD002 - Get all user with name filter
    */
    public function test_get_all_users_with_name_filter()
    {
        $this->seed_users_and_permissions();

        $result = $this->userService->getAllUsers(null, 'Regular');

        $this->assertCount(1, $result->items());
        $this->assertEquals('Regular User', $result->items()[0]->name);
    }

    /*
       TDD003 - Get all user with permission filter
    */
    public function test_get_all_users_with_combined_filters()
    {
        $this->seed_users_and_permissions();

        $result = $this->userService->getAllUsers('administrator', 'Regular');

        $this->assertCount(1, $result->items());
        $this->assertEquals('Regular User', $result->items()[0]->name);
    }

    // *********** FUNCTIONALITY: Register ***********
    /*
       TDD001 - User registers with valid data
    */
    public function test_create_user_success()
    {
        $user = User::factory()->make();
        $address = Address::factory()->make();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password123',
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

    /*
       TDD002 - User registers with invalid data
    */
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
