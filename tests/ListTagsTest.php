<?php

namespace Defro\Quotable\Tests;

use Defro\Quotable\Api;
use Defro\Quotable\Enum\Order;
use Defro\Quotable\Enum\SortBy;
use Defro\Quotable\Exception\QuotableException;
use GuzzleHttp\Client;

class ListTagsTest extends TestBase
{
    public function test_success()
    {
        $api = new Api(new Client());
        $result = $api->listTags();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        foreach ($result as $tag) {
            $this->assertIsArray($tag);
            $this->assertArrayHasKey('_id', $tag);
            $this->assertNotEmpty($tag['_id']);
            $this->assertArrayHasKey('name', $tag);
            $this->assertNotEmpty($tag['name']);
            $this->assertArrayHasKey('slug', $tag);
            $this->assertNotEmpty($tag['slug']);
            $this->assertArrayHasKey('quoteCount', $tag);
            $this->assertIsInt($tag['quoteCount'], 'Tag quote count is not a integer.');
            $this->assertArrayHasKey('dateAdded', $tag);
            $this->assertNotEmpty($tag['dateAdded']);
            $this->assertIsObject(\DateTimeImmutable::createFromFormat('Y-m-d', $tag['dateAdded']));
            $this->assertArrayHasKey('dateModified', $tag);
            $this->assertNotEmpty($tag['dateModified']);
            $this->assertIsObject(\DateTimeImmutable::createFromFormat('Y-m-d', $tag['dateModified']));
        }
    }

    public function test_sort_by()
    {
        $api = new Api(new Client());
        $this->assertNotEquals(
            $api->listTags(SortBy::DATE_ADDED, Order::DESC),
            $api->listTags(SortBy::QUOTE_COUNT),
            'Tags sorted by modified date and quote count are equals.'
        );
    }

    public function test_order()
    {
        $api = new Api(new Client());
        $this->assertNotEquals(
            $api->listTags(SortBy::NAME, Order::ASC),
            $api->listTags(SortBy::NAME, Order::DESC),
            'Tags ordered ascendant and descendant are equals.'
        );
    }

    public function test_invalid_sort()
    {
        $api = new Api(new Client());
        $this->expectException(QuotableException::class);
        $result = $api->listTags(SortBy::AUTHOR);
    }
}
