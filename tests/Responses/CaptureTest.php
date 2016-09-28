<?php

namespace Tests\Responses;

use Devpark\PayboxGateway\DirectResponseCode;
use Devpark\PayboxGateway\Responses\Capture;
use Tests\UnitTestCase;
use Mockery as m;

class CaptureTest extends UnitTestCase
{
    /** @test */
    public function getFields_it_gets_valid_fields()
    {
        $responseBody = 'foo=bar&a=b&c=d';

        $response = m::mock(Capture::class, [$responseBody])->makePartial();

        $fields = $response->getFields();
        $this->assertEquals([
            'foo' => 'bar',
            'a' => 'b',
            'c' => 'd',
        ], $fields);
    }

    /** @test */
    public function isSuccess_it_returns_true_when_success()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::SUCCESS;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function isSuccess_it_returns_false_when_fail()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::CONNECTION_FAILED;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertFalse($response->isSuccess());
    }

    /** @test */
    public function shouldBeRepeated_it_returns_false_when_success()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::SUCCESS;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertFalse($response->shouldBeRepeated());
    }

    /** @test */
    public function shouldBeRepeated_it_returns_false_when_other_error()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::INCOHERENCE_ERROR;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertFalse($response->shouldBeRepeated());
    }

    /** @test */
    public function shouldBeRepeated_it_returns_true_when_connection_failed()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::CONNECTION_FAILED;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertTrue($response->shouldBeRepeated());
    }

    /** @test */
    public function shouldBeRepeated_it_returns_true_when_timeout()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::TIMEOUT;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertTrue($response->shouldBeRepeated());
    }

    /** @test */
    public function shouldBeRepeated_it_returns_true_when_internal_timeout()
    {
        $responseBody = 'foo=bar&a=b&c=d&CODEREPONSE=' . DirectResponseCode::INTERNAL_TIMEOUT;
        $response = m::mock(Capture::class, [$responseBody])->makePartial();
        $this->assertTrue($response->shouldBeRepeated());
    }
}
