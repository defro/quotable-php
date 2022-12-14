<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use GuzzleHttp\Client;

class GetRandomQuoteTest extends TestBase
{
    public function test_success()
    {
        $api = new Api(new Client());
        $result = $api->getRandomQuote();
        $this->assertsQuote($result);
    }
}
