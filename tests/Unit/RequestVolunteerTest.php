<?php

namespace Tests\Unit;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;
use App\Interfaces\RequestVolunteerServiceInterface;
use App\Models\RequestVolunteer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RequestVolunteerTest extends TestCase
{
    use RefreshDatabase;

    protected RequestVolunteerServiceInterface $requestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->requestService = app(RequestVolunteerServiceInterface::class);
    }

    // *********** FUNCTIONALITY: create request volunteer ***********
    /*
        TDD001 - create a event with valid data
    */
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

    /*
        TDD002 - create a event with pending request
    */
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

    // *********** FUNCTIONALITY: get all request volunteer ***********
    /*
        TDD001 - list all request
    */
    public function test_list_all_requests()
    {
        RequestVolunteer::factory()->count(12)->create();

        $result = $this->requestService->listAllRequest();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(6, $result->items());
        $this->assertNotEmpty($result->items());

        foreach ($result->items() as $request) {
            $this->assertInstanceOf(RequestVolunteerResource::class, $request);
        }
    }   

    // *********** FUNCTIONALITY: update request volunteer ***********
    /*
        TDD001 - update request with valid data
    */
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

    /*
        TDD002 - update request without request
    */
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

    /*
        TDD003 - update request with request rejected
    */
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

    /*
        TDD004 - update request status with valid data
    */
    public function test_update_status_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);
    
        $requestVolunteer = RequestVolunteer::factory()->create([
            'status' => 'pending',
        ]);
        $user->update(['request_volunteer_id' => $requestVolunteer->id]);
    
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
    

    /*
        TDD005 - update request status with no existing request
    */
    public function test_update_status_with_nonexistent_request()
    {
        $data = ['status' => 'accepted'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Solicitação de voluntário não encontrada.");

        $this->requestService->updateStatus(999, $data);
    }

    public function test_get_by_id_requests()
    {
        RequestVolunteer::factory()->count(12)->create();

        $result = $this->requestService->getById(1);

        $this->assertInstanceOf(RequestVolunteerResource::class, $result);
        $this->assertNotEmpty($result);
    }  

    public function test_get_by_id_requests_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar todas as solicitações de voluntário.");
        
        $this->requestService->getById(1);
    }
}
