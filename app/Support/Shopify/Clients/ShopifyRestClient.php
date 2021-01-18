<?php

declare(strict_types=1);

namespace App\Support\Shopify\Clients;

use App\Support\Shopify\ShopifyClientInterface;
use App\Support\Shopify\PaginationLinks;
use App\Support\Shopify\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Fluent;
use Illuminate\Support\LazyCollection;
use Psr\Http\Message\ResponseInterface;

use function array_map;
use function json_decode;

class ShopifyRestClient implements ShopifyClientInterface
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * RestDriver constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Fetch the products from the Shopify API.
     *
     * @param array $params
     * @return Response
     *
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function products(array $params = []): Response
    {
        $response = $this->client->request('GET', 'products.json', [
            'query' => $params
        ]);

        // Keeping it simple for now, but I would also:
            // Handle rate limit
            // Handle 4xx
            // Handle 5xx

        $records = $this->parse($response, 'products');
        $paginationLinks = $this->pagination($response);

        return new Response($records, $paginationLinks);
    }

    /**
     * Fetch the orders from the Shopify API.
     *
     * @param array $params
     * @return Response
     *
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function orders(array $params = []): Response
    {
        $response = $this->client->request('GET', 'orders.json', [
            'query' => $params
        ]);

        // Keeping it simple for now, but I would also:
            // Handle rate limit
            // Handle 4xx
            // Handle 5xx

        $records = $this->parse($response, 'orders');
        $paginationLinks = $this->pagination($response);

        return new Response($records, $paginationLinks);
    }

    /**
     * Parse the response json.
     *
     * @param ResponseInterface $response
     * @param string $key
     * @return LazyCollection
     * @throws \JsonException
     */
    private function parse(ResponseInterface $response, string $key): LazyCollection
    {
        $contents = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return new LazyCollection(array_map(static function ($record) {
            return new Fluent($record);
        }, $contents[$key]));
    }

    /**
     * Hydrate a PaginationLink class from the Link header.
     *
     * @param ResponseInterface $response
     *
     * @return PaginationLinks
     */
    private function pagination(ResponseInterface $response): PaginationLinks
    {
        return new PaginationLinks($response->getHeader('Link')[0] ?? '');
    }
}
