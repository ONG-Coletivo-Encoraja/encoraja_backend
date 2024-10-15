<?php

namespace Tests\Unit;

use App\Http\Resources\Complaince\ComplainceResource;
use App\Interfaces\ComplainceServiceInterface;
use App\Services\ComplainceService;
use App\Models\Complaince;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ComplainceTest extends TestCase
{
    use RefreshDatabase;

    protected ComplainceServiceInterface $complaince_service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->complaince_service = app(ComplainceServiceInterface::class);
    }

    public function test_create_complaince()
    {
        $data = Complaince::factory()->make()->toArray();

        $ip_address = '127.0.0.1';
        $browser = 'Chrome';

        $complaince_resource = $this->complaince_service->create($data, $ip_address, $browser);

        $this->assertInstanceOf(ComplainceResource::class, $complaince_resource);
        $this->assertDatabaseHas('complainces', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_create_complaince_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Denúncia não cadastrada:");

        $this->complaince_service->create([], '127.0.0.1', 'Chrome');
    }

    public function test_get_all_complainces()
    {
        Complaince::factory()->count(5)->create();

        $complainces = $this->complaince_service->getAll();

        $this->assertEquals(5, $complainces->total());
    }

    public function test_get_all_complainces_throws_exception_when_empty()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Denúncias não encontradas.");

        $this->complaince_service->getAll();
    }

    public function test_get_by_id()
    {
        $complaince = Complaince::factory()->create();

        $complaince_resource = $this->complaince_service->getById($complaince->id);

        $this->assertInstanceOf(ComplainceResource::class, $complaince_resource);
        $this->assertEquals($complaince->id, $complaince_resource->id);
    }

    public function test_get_by_id_throws_exception_when_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Denúncia não encontrada.");

        $this->complaince_service->getById(999);
    }
}