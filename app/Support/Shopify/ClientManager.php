<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use App\Support\Shopify\Clients\ShopifyRestClient;
use GuzzleHttp\Client;
use Illuminate\Support\Manager;

final class ClientManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('shop.products.default', 'rest');
    }

    /**
     * Create an instance of a RestDriver.
     *
     * @return ShopifyClientInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function createRestDriver(): ShopifyClientInterface
    {
        return $this->container->make(ShopifyRestClient::class);
    }
}
