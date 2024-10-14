<?php

namespace Tests\Unit;

use App\Http\Resources\ReportAdmin\ReportAdminResource;
use App\Interfaces\EventServiceInterface;
use App\Interfaces\InscriptionServiceInterface;
use App\Interfaces\ReportAdminServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Event;
use App\Models\RelatesEvent;
use App\Models\ReportAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReportAdminTest extends TestCase
{
    use RefreshDatabase;

    protected UserServiceInterface $userService;
    protected EventServiceInterface $eventService;
    protected InscriptionServiceInterface $inscriptionService;
    protected ReportAdminServiceInterface $reportAdminService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->reportAdminService = app(ReportAdminServiceInterface::class);
    }

    public function test_create_report_admin_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $data = ReportAdmin::factory()->make(['event_id' => $event->id])->toArray();

        $result = $this->reportAdminService->create($data);

        $this->assertInstanceOf(ReportAdminResource::class, $result);
        $this->assertDatabaseHas('report_admins', [
            'description' => $data['description'],
            'relates_event_id' => $relatesEvent->id,
        ]);
    }

    public function test_create_report_admin_event_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Evento não encontrado.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $data = ReportAdmin::factory()->make(['event_id' => 999])->toArray();

        $this->reportAdminService->create($data);
    }

    public function test_create_report_admin_event_not_finished()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Evento não finalizado.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);
        RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $data = ReportAdmin::factory()->make(['event_id' => $event->id])->toArray();

        $this->reportAdminService->create($data);
    }

    public function test_create_report_admin_no_relation_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Relação de evento não encontrada.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);

        $data = ReportAdmin::factory()->make(['event_id' => $event->id])->toArray();

        $this->reportAdminService->create($data);
    }

    public function test_create_report_admin_report_exists()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Já existe um relatório enviado para este evento.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);
        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $data = ReportAdmin::factory()->make(['event_id' => $event->id])->toArray();

        $this->reportAdminService->create($data);
    }

    public function test_get_report_by_event_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);

        $event = Event::factory()->create();

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $result = $this->reportAdminService->getByEvent($event->id);

        $this->assertInstanceOf(ReportAdminResource::class, $result);
        $this->assertEquals($report->id, $result->id);
    }

    public function test_get_report_by_event_event_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Evento não encontrado.');

        $this->reportAdminService->getByEvent(999);
    }

    public function test_get_report_by_event_relation_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Relação de evento não encontrada.');

        $event = Event::factory()->create();

        $this->reportAdminService->getByEvent($event->id);
    }

    public function test_get_report_by_event_report_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Relatório de evento não encontrado.');

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $this->reportAdminService->getByEvent($event->id);
    }

    public function test_get_all_reports_success()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create(['name' => 'Evento Teste']);

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $result = $this->reportAdminService->getAll();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertCount(1, $result->getCollection());
        $this->assertEquals($report->id, $result->first()->id);
    }

    public function test_get_all_reports_no_reports()
    {
        $result = $this->reportAdminService->getAll();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertCount(0, $result->getCollection());
    }

    public function test_get_all_reports_with_event_name_filter()
    {
        $user = User::factory()->create();

        $event1 = Event::factory()->create(['name' => 'Evento Teste']);
        $event2 = Event::factory()->create(['name' => 'Outro Evento']);

        RelatesEvent::factory()->create(['event_id' => $event1->id, 'user_id' => $user->id]);
        RelatesEvent::factory()->create(['event_id' => $event2->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $event1->id]);

        $result = $this->reportAdminService->getAll('Teste');

        $this->assertCount(1, $result->getCollection());
        $this->assertEquals($report->id, $result->first()->id);
    }

    public function test_get_all_reports_with_no_matching_event_name()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create(['name' => 'Evento Teste']);

        RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        ReportAdmin::factory()->create(['relates_event_id' => $event->id]);

        $result = $this->reportAdminService->getAll('Evento Inexistente');

        $this->assertCount(0, $result->getCollection());
    }

    public function test_get_report_by_id_success()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create();

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $result = $this->reportAdminService->getById($report->id);

        $this->assertInstanceOf(ReportAdminResource::class, $result);
        $this->assertEquals($report->id, $result->id); 
    }

    public function test_get_report_by_id_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Relatório não encontrado.');

        $this->reportAdminService->getById(999);
    }

    public function test_update_report_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create();

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $data = [
            'qtt_person' => 10,
            'description' => 'Nova descrição',
            'results' => 'Novos resultados',
            'observation' => 'Novas observações',
        ];

        $result = $this->reportAdminService->update($report->id, $data);

        $this->assertInstanceOf(ReportAdminResource::class, $result);
        $this->assertEquals($data['description'], $result->description);
    }

    public function test_update_report_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Relatório não atualizado: Relatório não encontrado.');

        $this->reportAdminService->update(999, []);
    }

    public function test_update_report_without_permission()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create();

        $relatesEvent = RelatesEvent::factory()->create(['event_id' => $event->id, 'user_id' => $user->id]);

        $report = ReportAdmin::factory()->create(['relates_event_id' => $relatesEvent->id]);

        $anotherUser = User::factory()->create();

        $this->actingAs($anotherUser);

        $data = [
            'qtt_person' => 10,
            'description' => 'Nova descrição',
            'results' => 'Novos resultados',
            'observation' => 'Novas observações',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Apenas o responsável do evento ou um administrador pode editar o relatório.');
        $this->reportAdminService->update($report->id, $data);
    }
}
