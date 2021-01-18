<?php

declare(strict_types=1);

namespace App\Shop\Products\Listeners;

use App\Shop\Products\Events\ProductSyncComplete;
use App\Shop\Products\Events\ProductSyncRequest;
use App\Shop\Products\Transformers\ProductTransformer;
use App\Support\Shopify\ShopifyTransformerInterface;
use App\Support\Shopify\Sync;
use App\Support\Shopify\SyncRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class SyncProducts extends Sync implements ShouldQueue
{
    protected const THROTTLE_KEY = 'shop.product.sync.request';

    /**
     * Process the request.
     *
     * @param SyncRequest $event
     *
     * @throws Throwable
     */
    protected function process(SyncRequest $event): void
    {
        $client = $this->getClient($event);

        $response = $client->products($event->params);

        $this->syncRecords($response->records());

        if ($response->next()) {
            $this->dispatcher->dispatch(new ProductSyncRequest($event->driver, $response->next()->params()));
            return;
        }

        $this->dispatcher->dispatch(new ProductSyncComplete(now()));
    }

    /**
     * The record transformer instance.
     *
     * @return ShopifyTransformerInterface
     */
    protected function getTransformer(): ShopifyTransformerInterface
    {
        return new ProductTransformer($this->connection);
    }
}
