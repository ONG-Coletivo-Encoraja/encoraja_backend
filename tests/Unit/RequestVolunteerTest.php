<?php

namespace Tests\Unit;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;
use App\Interfaces\InscriptionServiceInterface;
use App\Interfaces\RequestVolunteerServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\RequestVolunteer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RequestVolunteerTest extends TestCase
{
    use RefreshDatabase;

    protected UserServiceInterface $userService;
    protected InscriptionServiceInterface $inscriptionService;
    protected RequestVolunteerServiceInterface $requestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = app(UserServiceInterface::class);
        $this->inscriptionService = app(InscriptionServiceInterface::class);
        $this->requestService = app(RequestVolunteerServiceInterface::class);
    }

    public function test_create_volunteer_request()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = RequestVolunteer::factory()->make()->toArray();

        $response = $this->requestService->create($data);

        $this->assertInstanceOf(RequestVolunteerResource::class, $response);
        $this->assertDatabaseHas('request_volunteers', ['status' => 'pending']);
        $this->assertDatabaseHas('users', ['request_volunteer_id' => $response->id]);
    }

    public function test_create_with_pending_request_throws_exception()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'beneficiary']);
        Auth::login($user);

        RequestVolunteer::factory()->create([
            'status' => 'pending',
        ]);

        $user->request_volunteer_id = 1;
        $user->save();

        $data = RequestVolunteer::factory()->make()->toArray();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Você já tem uma solicitação de voluntário pendente ou aceita.");

        $this->requestService->create($data);
    }

    public function test_list_all_requests()
    {
        RequestVolunteer::factory()->count(10)->create();

        $result = $this->requestService->listAllRequest();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(5, $result->items());
        $this->assertNotEmpty($result->items());

        foreach ($result->items() as $request) {
            $this->assertInstanceOf(RequestVolunteerResource::class, $request);
        }
    }

    public function test_update_volunteer_request_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $requestVolunteer = RequestVolunteer::factory()->create([
            'status' => 'accepted',
        ]);

        $user->request_volunteer_id = $requestVolunteer->id;
        $user->save();

        $data = RequestVolunteer::factory()->make()->toArray();

        $response = $this->requestService->update($data);

        $this->assertInstanceOf(RequestVolunteerResource::class, $response);
        $this->assertDatabaseHas('request_volunteers', [
            'id' => $requestVolunteer->id,
            'how_know' => $data['how_know'],
        ]);
    }

    public function test_update_with_no_volunteer_request()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = [
            'some_field' => 'new_value',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nenhuma solicitação de voluntário encontrada para o usuário.");

        $this->requestService->update($data);
    }

    public function test_update_with_request_not_accepted()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $requestVolunteer = RequestVolunteer::factory()->create([
            'status' => 'pending',
        ]);

        $user->request_volunteer_id = $requestVolunteer->id;
        $user->save();

        $data = RequestVolunteer::factory()->make()->toArray();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Sua solicitação ainda não foi aceita, não é possível atualizá-la.");

        $this->requestService->update($data);
    }

    public function test_update_status_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $requestVolunteer = RequestVolunteer::factory()->create([
            'status' => 'pending',
        ]);

        $data = ['status' => 'accepted'];

        $response = $this->requestService->updateStatus($requestVolunteer->id, $data);

        $this->assertInstanceOf(RequestVolunteerResource::class, $response);
        $this->assertDatabaseHas('request_volunteers', [
            'id' => $requestVolunteer->id,
            'status' => 'accepted',
        ]);
        $this->assertDatabaseHas('permissions', [
            'user_id' => $user->id,
            'type' => 'volunteer',
        ]);
    }

    public function test_update_status_with_nonexistent_request()
    {
        $data = ['status' => 'accepted'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Solicitação de voluntário não encontrada.");

        $this->requestService->updateStatus(999, $data);
    }
}
