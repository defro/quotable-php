<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use GuzzleHttp\Client;

class GetQuoteByIdTest extends TestBase
{
    public function test_success()
    {
        $api = new Api(new Client());
        $random = $api->getRandomQuote();
        $result = $api->getQuoteById($random['_id']);
        $this->assertsQuote($result);
    }
}
