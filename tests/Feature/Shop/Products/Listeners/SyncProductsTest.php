<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Products\Listeners;

use App\Shop\Products\Events\ProductSyncComplete;
use App\Shop\Products\Transformers\ProductTransformer;
use App\Support\Shopify\Clients\ShopifyRestClient;
use App\Shop\Products\Events\ProductSyncRequest;
use App\Shop\Products\Listeners\SyncProducts;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\CreatesApplication;

class SyncProductsTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     */
    public function it_will_make_an_attempt_to_paginate_the_records(): void
    {
        Event::fake();

        $json = file_get_contents(__DIR__ . '/../../../../fixtures/clients/spotify/products-paginated.json');
        $params = ['status' => 'active', 'limit' => 3];

        $message = Mockery::mock(ResponseInterface::class, static function ($mock) use ($json) {
            $stream = Mockery::mock(StreamInterface::class, static function ($mock) use ($json) {
                $mock->shouldReceive('getContents')->once()->andReturns($json);
            });

            $mock->shouldReceive('getBody')->once()->andReturns($stream);

            $mock->shouldReceive('getHeader')->once()->with('Link')->andReturns([
                '<https://foo.myshopify.com/admin/api/2020-04/products.json?page_info=opqrstu&limit=3>; rel="next"'
            ]);
        });

        $client = Mockery::mock(Client::class, static function ($mock) use ($message, $params) {
            $mock->shouldReceive('request')
                ->once()
                ->with('GET', 'products.json', ['query' => $params])
                ->andReturns($message);
        });

        $this->app->instance(ShopifyRestClient::class, new ShopifyRestClient($client));

        $transformer = Mockery::mock(ProductTransformer::class, static function ($mock) {
            $mock->shouldReceive('transform')->atLeast()->once();
        });

        $this->app->instance(ProductTransformer::class, $transformer);

        /** @var SyncProducts $listener */
        $listener = resolve(SyncProducts::class);

        $listener->handle(new ProductSyncRequest('rest', $params));

        Event::assertDispatched(ProductSyncRequest::class, static function (ProductSyncRequest $event) {
            return $event->params === ['page_info' => 'opqrstu', 'limit' => '3'];
        });

        Event::assertNotDispatched(ProductSyncComplete::class);
    }
}
