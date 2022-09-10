<?php

namespace Defro\Quotable;

use Defro\Quotable\Enum\Order;
use Defro\Quotable\Enum\SortBy;
use Defro\Quotable\Exception\BadStatusCodeException;
use Defro\Quotable\Exception\QuotableException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Api
{
    /** @var Client */
    private Client $client;

    /** @var string */
    private string $endpointUri = 'https://api.quotable.io';

    private string $author;
    private int $maxLength;
    private int $minLength;
    private string $tags;

    /**
     * Api constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRandomQuote()
    {
        $uri = $this->endpointUri . '/random';//.'?'.http_build_query($parameters);

        return $this->response($uri);
    }

    public function getQuoteById(string $id)
    {
        $uri = $this->endpointUri . '/quotes/' . $id;

        return $this->response($uri);
    }

    public function getAuthorBySlug(string $slug)
    {
        $uri = $this->endpointUri . '/authors/' . $slug;

        return $this->response($uri);
    }

    public function listQuotes(
        int $page = 1, int $limit = 20, Order $order = Order::ASC, SortBy $sortBy = SortBy::DATE_ADDED
    )
    {
        $uri = $this->endpointUri . '/quotes';//.'?'.http_build_query($parameters);

        return $this->response($uri);
    }

    public function listTags(
        SortBy $sortBy = SortBy::NAME, Order $order = Order::ASC
    )
    {
        // validate sort by value
        if (!in_array($sortBy, [SortBy::DATE_ADDED, SortBy::DATE_MODIFIED, SortBy::NAME, SortBy::QUOTE_COUNT])) {
            throw new QuotableException(
                sprintf('Sort by "%s" is not supported for this query.', $sortBy->value)
            );
        }

        $uri = $this->endpointUri . '/tags?' . http_build_query([
                'sortBy' => $sortBy->value,
                'order' => $order->value,
            ]);

        return $this->response($uri);
    }

    public function listAuthors(
        string $authorSlug = null, int $page = 1, int $limit = 20, SortBy $sortBy = SortBy::NAME, Order $order = Order::ASC
    )
    {
        // validate sort by value
        if (!in_array($sortBy, [SortBy::DATE_ADDED, SortBy::DATE_MODIFIED, SortBy::NAME, SortBy::QUOTE_COUNT])) {
            throw new QuotableException(
                sprintf('Sort by "%s" is not supported for this query.', $sortBy->value)
            );
        }

        $data = [
            'page' => $page,
            'limit' => $limit,
            'order' => $order->value,
            'sortBy' => $sortBy->value,
        ];
        if (!empty($authorSlug)) {
            $data['slug'] = $authorSlug;
        }
        $uri = $this->endpointUri . '/authors?' . http_build_query($data);

        return $this->response($uri);
    }

    private function response(string $uri): array
    {
        $response = $this->client->get($uri);

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusCodeException(
                sprintf('Could not connect to %s', $uri), $response->getStatusCode()
            );
        }

        $json = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        throw new QuotableException('JSON response cannot be decoded.');
    }
}
