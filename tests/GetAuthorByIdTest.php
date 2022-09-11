<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use GuzzleHttp\Client;

class GetAuthorByIdTest extends TestBase
{
    public function test_success()
    {
        $api = new Api(new Client());
        $random = $api->getRandomQuote();
        $result = $api->getAuthorBySlug($random['authorSlug']);
        $this->assertsQuote($result);
    }
}
