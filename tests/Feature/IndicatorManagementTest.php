<?php

namespace Tests\Feature;

use App\Models\Indicator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndicatorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_id_can_be_created()
    {
        $response = $this->post($this->uri(), $this->correctData());

        $response->assertStatus(201);
        $this->assertCount(1, Indicator::all());
        $this->assertEquals(16, strlen(Indicator::first()->code));
    }

    /** @test */
    public function a_type_is_required()
    {
        $response = $this->post($this->uri(), array_merge($this->correctData(), ['type' => null]));

        $response->assertStatus(400);
        $response->assertJsonValidationErrors('type');
    }

    /** @test */
    public function a_length_must_be_numeric()
    {
        $response = $this->post($this->uri(), array_merge($this->correctData(), ['length' => 'word']));

        $response->assertStatus(400);
        $response->assertJsonValidationErrors('length');
    }

    /** @test */
    public function a_length_must_be_at_least_8()
    {
        $response = $this->post($this->uri(), array_merge($this->correctData(), ['length' => 4]));

        $response->assertStatus(400);
        $response->assertJsonValidationErrors('length');
    }

    /** @test */
    public function a_wrong_type_passed()
    {
        $response = $this->post($this->uri(), array_merge($this->correctData(), ['type' => 'incorrect_type']));

        $response->assertStatus(400);
        $response->assertJsonValidationErrors('type');
    }

    /** @test */
    public function an_id_can_be_shown()
    {
        $response = $this->post($this->uri(), $this->correctData());
        $response->assertStatus(201);

        $id = Indicator::first();

        $response = $this->get($this->uri() . $id->id);

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJson($id->toArray());
    }

    /** @test */
    public function an_id_is_not_found()
    {
        $response = $this->post($this->uri(), $this->correctData());
        $response->assertStatus(201);

        $incorrect_id = 1234;
        $id = Indicator::first();

        $response = $this->get($this->uri() . $incorrect_id);

        $response->assertStatus(404);
        $response->assertJsonValidationErrors('id');
        $this->assertNotEquals($id->id, $incorrect_id);
    }

    /** @test */
    public function getting_the_same_id_twice_or_more_gives_the_same_code()
    {
        $response = $this->post($this->uri(), $this->correctData());
        $response->assertStatus(201);

        $id = Indicator::first();

        $response = $this->get($this->uri() . $id->id);
        $response->assertJson($id->toArray());

        $response = $this->get($this->uri() . $id->id);
        $response->assertJson($id->toArray());
    }

    private function uri(): string
    {
        return 'api/indicators/';
    }

    private function correctData(): array
    {
        return [
            'type' => 'alphanumeric',
            'length' => 16,
        ];
    }
}
