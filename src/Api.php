<?php

namespace Defro\Quotable;

use Defro\Google\StreetView\Exception\BadStatusCodeException;
use Defro\Google\StreetView\Exception\RequestException;
use Defro\Google\StreetView\Exception\UnexpectedStatusException;
use Defro\Google\StreetView\Exception\UnexpectedValueException;
use Defro\Quotable\Exception\QuotableException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Api
{
    /** @var Client */
    private Client $client;

    /** @var string */
    private string $endpointUri = 'https://api.quotable.io';

    private int $minLength;
    private int $maxLength;
    private string $tags;
    private string $author;

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

        $response = $this->client->get($uri);

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusCodeException(
                sprintf('Could not connect to %s', $uri), $response->getStatusCode()
            );
        }

        return $this->formatResponse($response);
    }

    public function getQuoteById(string $id)
    {
        $uri = $this->endpointUri . '/quotes/' . $id;

        $response = $this->client->get($uri);

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusCodeException(
                sprintf('Could not connect to %s', $uri), $response->getStatusCode()
            );
        }

        return $this->formatResponse($response);
    }

    public function listQuotes(int $page = 1, int $limit = 20, string $order = 'asc', string $sortBy = 'dateAdded')
    {
        $uri = $this->endpointUri . '/quotes';//.'?'.http_build_query($parameters);

        $response = $this->client->get($uri);

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusCodeException(
                sprintf('Could not connect to %s', $uri), $response->getStatusCode()
            );
        }

        return $this->formatResponse($response);
    }

    private function formatResponse(ResponseInterface $response): array
    {
        $json = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        throw new QuotableException('JSON response cannot be decoded.');
    }
}
