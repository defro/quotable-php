<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function test_construct()
    {
        $api = new Api(new Client());
        $this->assertIsObject($api);
        $this->assertObjectHasAttribute('client', $api);
    }

    protected function assertsQuote($result)
    {
        $this->assertIsArray($result);
        $this->assertArrayHasKey('_id', $result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('author', $result);
        $this->assertArrayHasKey('authorSlug', $result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertIsArray($result['tags']);
        $this->assertNotEmpty($result['tags']);
        $this->assertArrayHasKey('length', $result);
        $this->assertIsInt($result['length']);
        $this->assertArrayHasKey('dateAdded', $result);
        $this->assertIsObject(\DateTimeImmutable::createFromFormat('Y-m-d', $result['dateAdded']));
        $this->assertArrayHasKey('dateModified', $result);
        $this->assertIsObject(\DateTimeImmutable::createFromFormat('Y-m-d', $result['dateAdded']));
    }
}
