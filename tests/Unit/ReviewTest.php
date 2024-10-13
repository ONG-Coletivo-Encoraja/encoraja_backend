<?php

namespace Tests\Unit;

use App\Http\Resources\Reviews\ReviewResource;
use App\Interfaces\EventServiceInterface;
use App\Interfaces\InscriptionServiceInterface;
use App\Interfaces\ReviewServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Event;
use App\Models\Inscription;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected UserServiceInterface $userService;
    protected EventServiceInterface $eventService;
    protected InscriptionServiceInterface $inscriptionService;
    protected ReviewServiceInterface $reviewService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = app(UserServiceInterface::class);
        $this->eventService = app(EventServiceInterface::class);
        $this->inscriptionService = app(InscriptionServiceInterface::class);
        $this->reviewService = app(ReviewServiceInterface::class);
    }

    public function test_create_review_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);

        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event->id, 'present' => true]);

        $data = Reviews::factory()->make(['event_id' => $event->id])->toArray();

        $reviewResource = $this->reviewService->create($data);

        $this->assertNotNull($reviewResource->id);
        $this->assertEquals($data['rating'], $reviewResource->rating);
        $this->assertEquals($data['observation'], $reviewResource->observation);
    }

    public function test_create_review_event_not_finished()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Apenas eventos finalizados podem ser avaliados.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'active']);

        $data = Reviews::factory()->make(['event_id' => $event->id])->toArray();

        $this->reviewService->create($data);
    }

    public function test_create_review_user_not_present()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você não estava presente no evento.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);

        $data = Reviews::factory()->make(['event_id' => $event->id])->toArray();

        $this->reviewService->create($data);
    }

    public function test_create_review_already_reviewed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você já avaliou este evento.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $event = Event::factory()->create(['status' => 'finished']);

        Inscription::factory()->create(['user_id' => $user->id, 'event_id' => $event->id, 'present' => true]);

        Reviews::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $data = Reviews::factory()->make(['event_id' => $event->id])->toArray();

        $this->reviewService->create($data);
    }

    public function test_delete_review_success()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $review = Reviews::factory()->create(['user_id' => $user->id]);

        $result = $this->reviewService->delete($review->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'deleted_at' => now()]);
    }

    public function test_delete_review_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você não tem permissão para excluir esta avaliação.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $this->reviewService->delete(999);
    }

    public function test_delete_review_forbidden()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você não tem permissão para excluir esta avaliação.');

        $user = User::factory()->create();
        $user->permissions()->create(['type' => 'volunteer']);
        Auth::login($user);

        $otherUser = User::factory()->create();
        $review = Reviews::factory()->create(['user_id' => $otherUser->id]);

        $this->reviewService->delete($review->id);
    }

    public function test_get_reviews_by_event_success()
    {
        $event = Event::factory()->create();

        Reviews::factory()->count(10)->create(['event_id' => $event->id]);

        $result = $this->reviewService->getByEvent($event->id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(5, $result); 
        $this->assertEquals($event->id, $result->first()->event_id);
    }

    public function test_get_reviews_by_event_no_reviews()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Evento sem avaliações.');

        $event = Event::factory()->create();

        $this->reviewService->getByEvent($event->id);
    }

    public function test_get_review_by_id_success()
    {
        $review = Reviews::factory()->create();

        $result = $this->reviewService->getById($review->id);

        $this->assertInstanceOf(ReviewResource::class, $result);
        $this->assertEquals($review->id, $result->id); 
    }

    public function test_get_review_by_id_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Avaliação não encontrada.');

        $this->reviewService->getById(999);
    }
}
