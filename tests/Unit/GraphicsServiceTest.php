<?php

namespace Tests\Unit;

use App\Interfaces\GraphicsServiceInterface;
use App\Models\User;
use App\Models\Event;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphicsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GraphicsServiceInterface $graphicsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->graphicsService = app(GraphicsServiceInterface::class);
    }

    public function test_ethnicity_chart()
{
    User::factory()->create(['ethnicity' => 'white', 'status' => 'active']);
    User::factory()->create(['ethnicity' => 'black', 'status' => 'active']);
    User::factory()->create(['ethnicity' => 'black', 'status' => 'active']);
    User::factory()->create(['ethnicity' => 'asian', 'status' => 'inactive']);

    $response = $this->graphicsService->ethnicityChart();

    $this->assertEquals([
        'white' => 1,
        'black' => 2,
        'yellow' => 0,
        'mixed' => 0,
        'prefer not say' => 0,
    ], json_decode($response->getContent(), true));
}


    public function test_present_event_chart()
    {
        $event = Event::factory()->create(['status' => 'finished']);
        Inscription::factory()->create(['event_id' => $event->id, 'present' => true]);
        Inscription::factory()->create(['event_id' => $event->id, 'present' => false]);

        $response = $this->graphicsService->presentEventChart();

        $this->assertCount(1, json_decode($response->getContent(), true));
    }

    public function test_ratings_chart()
    {
        $event = Event::factory()->create(['status' => 'finished']);

        $response = $this->graphicsService->ratingsChart();

        $this->assertCount(1, json_decode($response->getContent(), true));
    }

    public function test_age_group_chart()
    {
        User::factory()->create(['date_birthday' => now()->subYears(20)]);
        User::factory()->create(['date_birthday' => now()->subYears(30)]);
        User::factory()->create(['date_birthday' => now()->subYears(40)]);
        User::factory()->create(['date_birthday' => now()->subYears(50)]);

        $response = $this->graphicsService->ageGroupChart();

        $this->assertEquals([
            '16-26' => 1,
            '27-36' => 1,
            '37-46' => 1,
            '47 ou mais' => 1,
        ], json_decode($response->getContent(), true));
    }

    public function test_participation_chart()
    {
        Inscription::factory()->create(['present' => true]);
        Inscription::factory()->create(['present' => false]);

        $response = $this->graphicsService->participationChart();

        $this->assertCount(1, json_decode($response->getContent(), true));
    }
}