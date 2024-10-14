<?php

namespace Tests\Unit;

use App\Http\Resources\Inscription\InscriptionResource;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\EventServiceInterface;
use App\Interfaces\InscriptionServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Event;
use App\Models\User;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class InscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected InscriptionServiceInterface $inscriptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->inscriptionService = app(InscriptionServiceInterface::class);
    }

    // *********** FUNCTIONALITY: create inscription ***********
    /*
        TDD001 - create a inscription on a event
    */
    public function test_create_inscription_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create([
            'status' => 'active',
            'vacancies' => 5,
        ]);

        $data = [
            'event_id' => $event->id,
        ];

        $inscriptionResource = $this->inscriptionService->createInscription($data);

        $this->assertInstanceOf(InscriptionResource::class, $inscriptionResource);
        $this->assertDatabaseHas('inscriptions', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    /*
        TDD002 - create inscription with invalid event
    */
    public function test_create_inscription_throws_exception_when_event_not_found()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = ['event_id' => 9999];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Evento não encontrado.');

        $this->inscriptionService->createInscription($data);
    }

    /*
        TDD003 - create inscription with inactive event
    */
    public function test_create_inscription_throws_exception_when_event_not_active()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'inactive']);

        $data = [
            'event_id' => $event->id,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível se inscriver nesse evento.');

        $this->inscriptionService->createInscription($data);
    }

    /*
        TDD004 - create inscription when already inscribed
    */
    public function test_create_inscription_throws_exception_when_already_inscribed()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);

        Inscription::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $data = [
            'event_id' => $event->id,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você já está inscrito neste evento.');

        $this->inscriptionService->createInscription($data);
    }

    /*
        TDD005 - create inscription when max capacity reached
    */
    public function test_create_inscription_throws_exception_when_max_capacity_reached()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create([
            'status' => 'active',
            'vacancies' => 1,
        ]);

        Inscription::factory()->create([
            'event_id' => $event->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $data = [
            'event_id' => $event->id,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Capacidade máxima de vagas atingida para este evento.');

        $this->inscriptionService->createInscription($data);
    }

    // *********** FUNCTIONALITY: delete inscription ***********
    /*
        TDD001 - delete a inscription on a event
    */
    public function test_delete_inscription_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $inscription = Inscription::factory()->create([
            'user_id' => $user->id,
        ]);

        $result = $this->inscriptionService->deleteInscription($inscription->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('inscriptions', ['id' => $inscription->id]);
    }

    /*
        TDD002 - delete a inscription on a event - not found
    */
    public function test_delete_inscription_throws_exception_when_not_found()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Inscrição não encontrada.');

        $this->inscriptionService->deleteInscription(9999);
    }

    /*
        TDD003 - delete a inscription on a event - not owner
    */
    public function test_delete_inscription_throws_exception_when_not_owner()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $otherUser = User::factory()->create();
        $inscription = Inscription::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você não tem permissão para cancelar inscrição de outros usuário');

        $this->inscriptionService->deleteInscription($inscription->id);
    }

    // *********** FUNCTIONALITY: get my inscriptions ***********
    /*
        TDD001 - get my inscriptions
    */
    public function test_get_my_inscription_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        Inscription::factory()->count(3)->create(['user_id' => $user->id]);

        $result = $this->inscriptionService->getMyInscription();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

        $this->assertCount(3, $result);
        foreach ($result as $inscriptionResource) {
            $this->assertInstanceOf(InscriptionResource::class, $inscriptionResource);
        }
    }

    /*
        TDD002 - get my inscriptions - not found
    */
    public function test_get_my_inscription_no_inscriptions()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $result = $this->inscriptionService->getMyInscription();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

        $this->assertCount(0, $result);
    }

    // *********** FUNCTIONALITY: get by id ***********
    /*
        TDD001 - get inscriptions by id
    */
    public function test_get_by_id_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $inscription = Inscription::factory()->create(['user_id' => $user->id]);

        $result = $this->inscriptionService->getById($inscription->id);

        $this->assertInstanceOf(InscriptionResource::class, $result);
        $this->assertEquals($inscription->id, $result->id);
    }

    /*
        TDD002 - get inscriptions by id - not found
    */
    public function test_get_by_id_throws_exception_when_not_found()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Inscrição não encontra.");

        $this->inscriptionService->getById(9999);
    }

    // *********** FUNCTIONALITY: get by event id ***********
    /*
        TDD001 - get inscriptions by event id
    */
    public function test_get_inscriptions_by_event_id_success()
    {
        $event = Event::factory()->create();

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);

        Inscription::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $result = $this->inscriptionService->getInscriptionsByEventId($event->id);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /*
        TDD002 - get inscriptions by event id - not found
    */
    public function test_get_inscriptions_by_event_id_throws_exception_when_event_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar inscrições: Evento não encontrado.");

        $this->inscriptionService->getInscriptionsByEventId(9999);
    }

    // *********** FUNCTIONALITY: get all inscriptions ***********
    /*
        TDD001 - get all inscriptions
    */
    public function test_get_all_inscriptions_without_filters()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);

        $event = Event::factory()->create(['name' => 'Event One']);

        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $result = $this->inscriptionService->getAllInscriptions();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /*
        TDD001 - get all inscription with status filter
    */
    public function test_get_all_inscriptions_with_status_filter()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        $event = Event::factory()->create(['name' => 'Event Two']);

        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event->id, 'status' => 'approved']);
        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event->id, 'status' => 'pending']);

        $result = $this->inscriptionService->getAllInscriptions('approved');

        $this->assertEquals(1, $result->total());
        $this->assertEquals('approved', $result->first()->status);
    }

    /*
        TDD002 - get all inscriptions with name filter
    */
    public function test_get_all_inscriptions_with_event_name_filter()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        $event1 = Event::factory()->create(['name' => 'Special Event']);
        $event2 = Event::factory()->create(['name' => 'Another Event']);

        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event1->id]);
        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event2->id]);

        $result = $this->inscriptionService->getAllInscriptions(null, 'Special Event');

        $this->assertEquals(1, $result->total());
        $this->assertEquals($event1->id, $result->first()->event_id);
    }

    /*
        TDD003 - get all inscriptions with username filter
    */
    public function test_get_all_inscriptions_with_user_name_filter()
    {
        $user1 = User::factory()->create(['name' => 'Charlie']);
        $user1->permissions()->create(['type' => 'volunteer']);

        $user2 = User::factory()->create(['name' => 'Dave']);
        $user2->permissions()->create(['type' => 'volunteer']);

        $event = Event::factory()->create(['name' => 'Unique Event']);

        Inscription::factory()->create(['user_id' => $user1->id, 'event_id' => $event->id]);
        Inscription::factory()->create(['user_id' => $user2->id, 'event_id' => $event->id]);

        $result = $this->inscriptionService->getAllInscriptions(null, null, 'Charlie');

        $this->assertEquals(1, $result->total());
        $this->assertEquals($user1->id, $result->first()->user_id);
    }

    /*
        TDD004 - get all inscriptions - not found
    */
    public function test_get_all_inscriptions_throws_exception_when_no_inscriptions_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nenhuma inscrição foi encontrada.");

        $this->inscriptionService->getAllInscriptions();
    }

    // *********** FUNCTIONALITY: update inscriptions ***********
    /*
        TDD001 - update inscriptions
    */
    public function test_update_inscription_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);
        $inscription = Inscription::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $data = ['status' => 'approved'];

        $updatedInscriptionResource = $this->inscriptionService->update($inscription->id, $data);

        $this->assertInstanceOf(InscriptionResource::class, $updatedInscriptionResource);
        $this->assertEquals('approved', $updatedInscriptionResource->status);
        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'status' => 'approved',
        ]);
    }

    /*
        TDD002 - update inscriptions - not found
    */
    public function test_update_inscription_throws_exception_when_inscription_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar inscrição, inscrição não encontrada.");

        $data = ['status' => 'approved'];
        $this->inscriptionService->update(9999, $data); 
    }

    /*
        TDD003 - update inscriptions  error 
    */
    public function test_update_inscription_throws_exception_on_general_error()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);
        $inscription = Inscription::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $data = ['status' => null];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar inscrições:");

        $this->inscriptionService->update($inscription->id, $data);
    }

    // *********** FUNCTIONALITY: change present ***********
    /*
        TDD001 - change present true/false
    */
    public function test_present_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);
        $inscription = Inscription::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'present' => false,
        ]);

        $inscriptionResource = $this->inscriptionService->present($inscription->id);

        $this->assertInstanceOf(InscriptionResource::class, $inscriptionResource);
        $this->assertTrue($inscriptionResource->present);
        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'present' => true,
        ]);
    }

    /*
        TDD002 - change present - not found
    */
    public function test_present_throws_exception_when_inscription_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao encontrar inscrição, inscrição não encontrada.");

        $this->inscriptionService->present(9999);
    }
}
