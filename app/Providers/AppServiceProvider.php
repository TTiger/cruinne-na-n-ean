<?php

declare(strict_types=1);

namespace App\Providers;

use App\Shop\Orders\Listeners\SyncOrders;
use App\Shop\Orders\Transformers\OrderTransformer;
use App\Shop\Products\Listeners\SyncProducts;
use App\Shop\Products\Transformers\ProductTransformer;
use App\Support\Shopify\ClientManager;
use App\Support\Shopify\Clients\ShopifyRestClient;
use GuzzleHttp\Client;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRestClient();
        $this->registerProductTransformer();
        $this->registerOrderTransformer();
        $this->registerProductSync();
        $this->registerOrderSync();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function registerRestClient()
    {
        $this->app->singleton(ShopifyRestClient::class, function ($app) {
            $config = $app->config->get('shop.products.clients.rest');

            $client = new Client([
                'base_uri' => $config['base_uri'],
                'auth' => [$config['api_key'], $config['app_password']]
            ]);

            return new ShopifyRestClient($client);
        });
    }

    private function registerProductTransformer()
    {
        $this->app->singleton(ProductTransformer::class, function (Application $app) {
            return new ProductTransformer(
                $app->make(Connection::class)
            );
        });
    }

    private function registerOrderTransformer()
    {
        $this->app->singleton(OrderTransformer::class, function (Application $app) {
            return new OrderTransformer(
                $app->make(Connection::class)
            );
        });
    }

    private function registerProductSync()
    {
        $this->app->singleton(SyncProducts::class, function (Application $app) {
            return new SyncProducts(
                $app->make(ClientManager::class),
                $app->make(ProductTransformer::class),
                $app->make(Connection::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    private function registerOrderSync()
    {
        $this->app->singleton(SyncOrders::class, function (Application $app) {
            return new SyncProducts(
                $app->make(ClientManager::class),
                $app->make(OrderTransformer::class),
                $app->make(Connection::class),
                $app->make(Dispatcher::class)
            );
        });
    }
}
