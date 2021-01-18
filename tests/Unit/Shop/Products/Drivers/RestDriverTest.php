<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Products\Drivers;

use App\Support\Shopify\Clients\ShopifyRestClient;
use GuzzleHttp\Client;
use Illuminate\Support\Fluent;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class RestDriverTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_hydrate_products_into_a_shopify_response_instance(): void
    {
        $json = file_get_contents(__DIR__ . '/../../../../fixtures/clients/spotify/products.json');
        $params = ['status' => 'active', 'limit' => 5];

        $stream = Mockery::mock(StreamInterface::class, static function ($mock) use ($json) {
            $mock->shouldReceive('getContents')->once()->andReturns($json);
        });

        $message = Mockery::mock(ResponseInterface::class, static function ($mock) use ($stream) {
            $mock->shouldReceive('getHeader')->once()->with('Link')->andReturns(
                ['<https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=5&page_info=eyJsYXN0X2lkIjo3NTg3NTYxNDc0MCwibGFzdF92YWx1ZSI6IkdpZnQgQ2FyZCIsImRpcmVjdGlvbiI6Im5leHQifQ>; rel="next"']
            );
            $mock->shouldReceive('getBody')->once()->andReturns($stream);
        });

        $client = Mockery::mock(Client::class, static function ($mock) use ($message, $params) {
            $mock->shouldReceive('request')
                ->once()
                ->with('GET', 'products.json', ['query' => $params])
                ->andReturns($message);
        });

        $driver = new ShopifyRestClient($client);

        $response = $driver->products($params);
        $records = $response->records();
        $firstResult = $records->first();

        self::assertInstanceOf(Fluent::class, $firstResult);
        self::assertEquals('Colorful Birds', $firstResult->title);
        self::assertEquals(
            'https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=5&page_info=eyJsYXN0X2lkIjo3NTg3NTYxNDc0MCwibGFzdF92YWx1ZSI6IkdpZnQgQ2FyZCIsImRpcmVjdGlvbiI6Im5leHQifQ',
            (string) $response->next()
        );
    }
}
