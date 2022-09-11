<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use Defro\Quotable\Exception\QuotableException;
use GuzzleHttp\Client;

class ApiTest extends TestBase
{
    public function test_construct_production()
    {
        $api = new Api(new Client());
        $this->assertIsObject($api);
        $this->assertObjectHasAttribute('client', $api);
    }

    public function test_construct_staging()
    {
        $api = new Api(new Client(), 'staging');
        $this->assertIsObject($api);
        $this->assertObjectHasAttribute('endpointUri', $api);
    }

    public function test_construct_exception()
    {
        $this->expectException(QuotableException::class);
        $api = new Api(new Client(), 'unknown one');
    }
}
