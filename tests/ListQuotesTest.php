<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use GuzzleHttp\Client;

class ListQuotesTest extends BaseTest
{
    public function test_success()
    {
        $api = new Api(new Client());
        $result = $api->listQuotes();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('count', $result);
        $this->assertIsInt($result['count']);
        $this->assertArrayHasKey('totalCount', $result);
        $this->assertIsInt($result['totalCount']);
        $this->assertArrayHasKey('page', $result);
        $this->assertIsInt($result['page']);
        $this->assertArrayHasKey('totalPages', $result);
        $this->assertIsInt($result['totalPages']);
        $this->assertArrayHasKey('lastItemIndex', $result);
        $this->assertIsInt($result['lastItemIndex']);

        $this->assertArrayHasKey('results', $result);
        $this->assertIsArray($result['results']);
        $this->assertNotEmpty($result['results']);
        foreach ($result['results'] as $item) {
            $this->assertsQuote($item);
        }
    }
}
