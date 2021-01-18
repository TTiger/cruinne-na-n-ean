<?php

declare(strict_types=1);

namespace App\Shop\Orders\Listeners;

use App\Shop\Orders\Events\OrderSyncRequest;
use App\Shop\Orders\Events\OrderSyncComplete;
use App\Support\Shopify\Sync;
use App\Support\Shopify\SyncRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class SyncOrders extends Sync implements ShouldQueue
{
    protected const THROTTLE_KEY = 'shop.order.sync.request';

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

        $response = $client->orders($event->params);

        logger((string) $response->next());

        $this->syncRecords($response->records());

        if ($response->next()) {
            $this->dispatcher->dispatch(new OrderSyncRequest($event->driver, $response->next()->params()));
        }

        if (! $response->next()) {
            $this->dispatcher->dispatch(new OrderSyncComplete(now()));
        }
    }
}
