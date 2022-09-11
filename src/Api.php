<?php

namespace Defro\Quotable;

use Defro\Quotable\Enum\Order;
use Defro\Quotable\Enum\SortBy;
use Defro\Quotable\Exception\BadStatusCodeException;
use Defro\Quotable\Exception\QuotableException;
use GuzzleHttp\Client;

/**
 * Class Api
 *
 * @see
 */
class Api
{
    /** @var Client */
    private Client $client;

    /** @var string */
    private string $endpointUri;

    /**
     * Api constructor.
     *
     * @param Client $client
     * @param string $server
     */
    public function __construct(Client $client, string $server = 'production')
    {
        $this->client = $client;

        if ($server === 'production') {
            $this->endpointUri = 'https://api.quotable.io';
        }
        elseif ($server === 'staging') {
            $this->endpointUri = 'https://staging.quotable.io';
        }
        else {
            throw new QuotableException(sprintf(
                'Quotable server "%s" is unknown.', $server
            ));
        }
    }

    /**
     * Returns a single random quote from the database
     *
     * @param string|null $tags list of one or more tag names, separated by a comma (meaning AND) or a pipe (meaning OR).
     * @param string|null $author can be an author name or slug. To include quotes by multiple authors, provide a pipe-separated list of author names/slugs.
     * @param int|null $maxLength maximum Length in characters
     * @param int|null $minLength minimum Length in characters
     * @return array
     */
    public function getRandomQuote(
        ?string $tags = null,
        ?string $author = null,
        ?int $maxLength = null,
        ?int $minLength = null,
    ): array
    {
        $data = [];
        if (!empty($tags)) {
            $data['tags'] = $tags;
        }
        if (!empty($author)) {
            $data['author'] = $author;
        }
        if (!empty($minLength)) {
            $data['minLength'] = $minLength;
        }
        if (!empty($maxLength)) {
            $data['maxLength'] = $maxLength;
        }

        $uri = $this->endpointUri . '/random'. (empty($data) ? '' : '?'.http_build_query($data));

        return $this->response($uri);
    }

    /**
     * Get a quote by its ID.
     *
     * @param string $id
     * @return array
     */
    public function getQuoteById(string $id): array
    {
        $uri = $this->endpointUri . '/quotes/' . $id;

        return $this->response($uri);
    }

    /**
     * Get a single Author by slug.
     * This method can be used to get author details such as bio, website link, and profile image.
     *
     * @param string $slug
     * @return array
     */
    public function getAuthorBySlug(string $slug): array
    {
        $uri = $this->endpointUri . '/authors/' . $slug;

        return $this->response($uri);
    }

    /**
     * Get all quotes matching a given query.
     * By default, this will return a paginated list of all quotes, sorted by _id.
     * Quotes can also be filter by author, tag, and length.
     *
     * @param string|null $tags
     * @param string|null $author
     * @param int|null $maxLength
     * @param int|null $minLength
     * @param int $page
     * @param int $limit
     * @param Order $order
     * @param SortBy $sortBy
     * @return array
     */
    public function listQuotes(
        ?string $tags = null,
        ?string $author = null,
        ?int $maxLength = null,
        ?int $minLength = null,
        int $page = 1,
        int $limit = 20,
        Order $order = Order::ASC,
        SortBy $sortBy = SortBy::DATE_ADDED
    )
    {
        $data = [
            'page' => $page,
            'limit' => $limit,
            'order' => $order,
            'sortBy' => $sortBy,
        ];
        if (!empty($tags)) {
            $data['tags'] = $tags;
        }
        if (!empty($author)) {
            $data['author'] = $author;
        }
        if (!empty($minLength)) {
            $data['minLength'] = $minLength;
        }
        if (!empty($maxLength)) {
            $data['maxLength'] = $maxLength;
        }

        $uri = $this->endpointUri . '/quotes?'.http_build_query($data);

        return $this->response($uri);
    }

    /**
     * Get a list of all tags
     *
     * @param SortBy $sortBy
     * @param Order $order
     * @return array
     */
    public function listTags(
        SortBy $sortBy = SortBy::NAME, Order $order = Order::ASC
    ): array
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

    /**
     * Get all authors matching the given query.
     * This endpoint can be used to list authors, with several options for sorting and filter.
     * It can also be used to get author details for one or more specific authors, using the author slug or ids.
     *
     * @param string|null $authorSlug
     * @param int $page
     * @param int $limit
     * @param SortBy $sortBy
     * @param Order $order
     * @return array
     */
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
