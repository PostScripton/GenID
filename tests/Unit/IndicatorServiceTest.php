<?php

namespace Tests\Unit;

use App\Services\IndicatorService;
use PHPUnit\Framework\TestCase;

class IndicatorServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new IndicatorService();
    }

    /** @test */
    public function generate_id_with_string_method()
    {
        $id = $this->service->string();

        $this->assertEquals(1, preg_match('/^[A-Za-z]+$/', $id));
        $this->assertEquals(8, strlen($id));
    }

    /** @test */
    public function generate_id_with_number_method()
    {
        $id = $this->service->number();

        $this->assertEquals(1, preg_match('/^[0-9]+$/', $id));
        $this->assertEquals(8, strlen($id));
    }

    /** @test */
    public function generate_id_with_alphanumeric_method()
    {
        $id = $this->service->alphanumeric();

        $this->assertEquals(1, preg_match('/^[A-Za-z0-9]+$/', $id));
        $this->assertEquals(8, strlen($id));
    }

    /** @test */
    public function generate_id_with_guid_method()
    {
        $id = $this->service->guid();

        $this->assertEquals(1,
            preg_match('/^[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}$/', $id));
        $this->assertEquals(36, strlen($id));
    }
    
    /** @test */
    public function randomizing_type_is_not_correct()
    {
        $string = $this->service->randomize('string', 8);
        $number = $this->service->randomize('number', 8);
        $guid = $this->service->randomize('guid', 8);
        $alphanumeric = $this->service->randomize('alphanumeric', 8);

        $incorrect = $this->service->randomize('incorrect_type', 8);
        $this->assertFalse($incorrect);

        // Testing others to be correct
        $this->assertIsString($string);
        $this->assertIsString($number);
        $this->assertIsString($guid);
        $this->assertIsString($alphanumeric);
    }
}