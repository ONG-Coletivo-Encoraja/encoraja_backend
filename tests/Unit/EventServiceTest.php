<?php

namespace Tests\Unit;

use App\Http\Resources\Event\EventResource;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\EventServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Event;
use App\Models\RelatesEvent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserServiceInterface $userService;
    protected AuthServiceInterface $authService;
    protected EventServiceInterface $eventService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = app(UserServiceInterface::class);
        $this->eventService = app(EventServiceInterface::class);
        $this->authService = app(AuthServiceInterface::class);
    }

    private function seed_users_and_permissions()
    {
        $userAdmin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com']);
        $userBene = User::factory()->create(['name' => 'Bene User', 'email' => 'bene@example.com']);
        $userVolun = User::factory()->create(['name' => 'Volun User', 'email' => 'volu@example.com']);

        $userAdmin->permissions()->create(['type' => 'administrator']);
        $userBene->permissions()->create(['type' => 'beneficiary']);
        $userVolun->permissions()->create(['type' => 'volunteer']);
    }

    // *********** FUNCTIONALITY: create event ***********
    /*
        TDD001 - User administrator create a event with valid data
    */
    public function test_create_admin_success()
    {
        $this->seed_users_and_permissions();
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'administrator']);

        $event_data = Event::factory()->make([
            'owner' => 3,
        ])->toArray();

        $event_resource = $this->eventService->createAdmin($event_data);

        $this->assertInstanceOf(EventResource::class, $event_resource);
        $this->assertDatabaseHas('events', ['name' => $event_data['name']]);
    }

    /*
        TDD002 - User administrator create a event with invalid status
    */
    public function test_create_admin_throws_exception_on_inactive_status()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O evento não pode ser criado com status 'inativo' ou 'finalizado'");

        $data = [
            'status' => 'inactive',
            'owner' => 1,
        ];

        $this->eventService->createAdmin($data);
    }

    /*
        TDD003 - User administrator create a event with invalid owner
    */
    public function test_create_admin_throws_exception_when_user_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Usuário responsável não encontrado.");

        $data = [
            'name' => 'Evento Teste',
            'description' => 'Descrição do evento',
            'date' => '2024-10-20',
            'time' => '10:00',
            'modality' => 'Presencial',
            'status' => 'active',
            'type' => 'Workshop',
            'target_audience' => 'Jovens',
            'vacancies' => 50,
            'interest_area' => 'Educação',
            'price' => 100.00,
            'workload' => 4,
            'owner' => 9999,
        ];

        $this->eventService->createAdmin($data);
    }

    /*
        TDD004 - User beneficiary try create a event
    */
    public function test_create_admin_throws_exception_when_user_is_beneficiary()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'beneficiary']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Usuário não pode ser cadastrado como responsável.");

        $data = [
            'name' => 'Evento Teste',
            'description' => 'Descrição do evento',
            'date' => '2024-10-20',
            'time' => '10:00',
            'modality' => 'Presencial',
            'status' => 'active',
            'type' => 'Workshop',
            'target_audience' => 'Jovens',
            'vacancies' => 50,
            'interest_area' => 'Educação',
            'price' => 100.00,
            'workload' => 4,
            'owner' => $user->id,
        ];

        $this->eventService->createAdmin($data);
    }

    /*
        TDD005 - User volunteer create a event with valid data
    */
    public function test_create_volunteer_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = Event::factory()->make([
            'status' => 'pending',
            'owner' => $user->id,
        ])->toArray();

        $eventResource = $this->eventService->createVolunteer($data);

        $this->assertInstanceOf(EventResource::class, $eventResource);
        $this->assertDatabaseHas('events', ['name' => $data['name']]);
    }

    /*
        TDD006 - User volunteer create a event with invalid status
    */
    public function test_create_volunteer_throws_exception_on_invalid_status()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Evento não cadastrado: O evento só pode ser cadastrado com o status pendente.");

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = Event::factory()->make([
            'status' => 'active',
            'owner' => $user->id,
        ])->toArray();

        $this->eventService->createVolunteer($data);
    }

    /*
        TDD007 - User volunteer try create a event with invalid user id
    */
    public function test_create_volunteer_throws_exception_on_invalid_owner()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Evento não cadastrado: Você só pode criar eventos que são atribuidos a você.");

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = Event::factory()->make([
            'status' => 'pending',
            'owner' => 9999,
        ])->toArray();

        $this->eventService->createVolunteer($data);
    }

    // *********** FUNCTIONALITY: update event ***********
    /*
        TDD001 - User administrator update event
    */
    public function test_update_admin_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'administrator']);
        Auth::login($user);

        $event = Event::factory()->create();

        Auth::login($user);

        $data = [
            'name' => 'Evento Atualizado',
            'description' => 'Descrição Atualizada',
            'owner' => $user->id,
        ];

        $eventResource = $this->eventService->updateAdmin($event->id, $data);

        $this->assertInstanceOf(EventResource::class, $eventResource);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'name' => 'Evento Atualizado']);
    }

    /*
        TDD002 - User administrator try update event with invalid user id
    */
    public function test_update_admin_throws_exception_when_user_not_found()
    {
        $event = Event::factory()->create();

        $data = [
            'owner' => 9999,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Usuário responsável não encontrado.");

        $this->eventService->updateAdmin($event->id, $data);
    }

    /*
        TDD003 - User administrator try update event with invalid event id
    */
    public function test_update_admin_throws_exception_when_event_not_found()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'administrator']);
        Auth::login($user);

        $data = [
            'owner' => 1,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Evento não encontrado.");

        $this->eventService->updateAdmin(9999, $data);
    }

    /*
        TDD004 - User volunteer try update event with valid data
    */
    public function test_update_volunteer_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create([
            'status' => 'active',
        ]);

        $event->relatesEvents()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Evento Atualizado',
            'description' => 'Descrição Atualizada',
        ];

        $eventResource = $this->eventService->updateVolunteer($event->id, $data);

        $this->assertInstanceOf(EventResource::class, $eventResource);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'name' => 'Evento Atualizado']);
    }

    /*
        TDD005 - User volunteer try update event without relates event
    */
    public function test_update_volunteer_throws_exception_when_user_not_authorized()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create([
            'status' => 'active',
        ]);

        $anotherUser = User::factory()->create();
        $event->relatesEvents()->create(['user_id' => $anotherUser->id]);

        $data = [
            'name' => 'Evento Atualizado',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Você não tem permissão para editar este evento.");

        $this->eventService->updateVolunteer($event->id, $data);
    }

    // *********** FUNCTIONALITY: delete event ***********
    /*
        TDD001 - User detele event
    */
    public function test_delete_success()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();
        $event->relatesEvents()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertDatabaseHas('relates_events', ['event_id' => $event->id]);

        $result = $this->eventService->delete($event->id);

        $this->assertTrue($result);

        $this->assertSoftDeleted('events', ['id' => $event->id]);
        $this->assertSoftDeleted('relates_events', ['event_id' => $event->id]);
    }

    /*
        TDD002 - User detele invalid event
    */
    public function test_delete_throws_exception_when_event_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Evento não deletado: Evento não encontrado.");

        $this->eventService->delete(9999);
    }

    // *********** FUNCTIONALITY: get all event ***********
    /*
        TDD001 - get all filtered by events status
    */
    public function test_get_all_events_success()
    {
        Event::factory()->create(['status' => 'active', 'name' => 'Evento 1']);
        Event::factory()->create(['status' => 'active', 'name' => 'Evento 2']);
        Event::factory()->create(['status' => 'inactive', 'name' => 'Evento 3']);

        $paginator = $this->eventService->getAll('active');

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertCount(2, $paginator->items());
        $this->assertEquals('Evento 1', $paginator->items()[0]->name);
    }

    /*
        TDD002 - get all filtered by event name
    */
    public function test_get_all_events_with_name_filter()
    {
        Event::factory()->create(['status' => 'active', 'name' => 'Festival de Música']);
        Event::factory()->create(['status' => 'active', 'name' => 'Seminário de Tecnologia']);
        Event::factory()->create(['status' => 'inactive', 'name' => 'Feira de Negócios']);

        $paginator = $this->eventService->getAll(null, 'Música');

        $this->assertCount(1, $paginator->items());
        $this->assertEquals('Festival de Música', $paginator->items()[0]->name);
    }

    /*
        TDD003 - get all events - empty
    */
    public function test_get_all_events_throws_exception_when_no_events_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nenhum evento foi encontrado.");

        $this->eventService->getAll();
    }

    // *********** FUNCTIONALITY: get by id ***********
    /*
        TDD001 - get event by id
    */
    public function test_get_by_id_success()
    {
        $event = Event::factory()->create(['name' => 'Evento Teste']);

        $eventResource = $this->eventService->getById($event->id);

        $this->assertInstanceOf(EventResource::class, $eventResource);
        $this->assertEquals('Evento Teste', $eventResource->name);
    }

    /*
        TDD002 - get event by id - not found
    */
    public function test_get_by_id_throws_exception_when_event_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar evento: Evento não encontrado.");

        $this->eventService->getById(9999);
    }

    // *********** FUNCTIONALITY: get by logged user ***********
    /*
        TDD001 - get event by id
    */
    public function test_get_events_logged_user_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event1 = Event::factory()->create();
        $event2 = Event::factory()->create();
        RelatesEvent::factory()->create(['user_id' => $user->id, 'event_id' => $event1->id]);
        RelatesEvent::factory()->create(['user_id' => $user->id, 'event_id' => $event2->id]);

        $relates = $this->eventService->getEventsLoggedUser();

        $this->assertInstanceOf(LengthAwarePaginator::class, $relates);
        $this->assertCount(2, $relates->items());
    }

    /*
        TDD002 - get event by id - empty
    */
    public function test_get_events_logged_user_returns_empty()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nenhum evento foi encontrado.");

        $this->eventService->getEventsLoggedUser();
    }
}
