<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Fluent;
use Illuminate\Support\LazyCollection;
use Throwable;

abstract class Sync
{
    use InteractsWithQueue;

    protected const THROTTLE_TIMES = 2;
    protected const THROTTLE_EVERY = 1;

    /**
     * @var ClientManager
     */
    protected ClientManager $manager;

    /**
     * @var ShopifyTransformerInterface
     */
    protected ShopifyTransformerInterface $transformer;

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * SyncProducts constructor.
     *
     * @param ClientManager $manager
     * @param ShopifyTransformerInterface $transformer
     * @param Connection $connection
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        ClientManager $manager,
        ShopifyTransformerInterface $transformer,
        Connection $connection,
        Dispatcher $dispatcher
    ) {
        $this->manager = $manager;
        $this->transformer = $transformer;
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the product synchronization request.
     *
     * @param SyncRequest $event
     *
     * @return void
     * @throws LimiterTimeoutException
     */
    public function handle(SyncRequest $event): void
    {
        Redis::throttle(static::THROTTLE_KEY)
            ->allow(static::THROTTLE_TIMES)
            ->every(static::THROTTLE_EVERY)
            ->then(function () use ($event) {
                $this->process($event);
            }, function () {
                $this->release();
            });
    }

    /**
     * The Shopify API client instance.
     *
     * @param SyncRequest $event
     *
     * @return ShopifyClientInterface
     */
    protected function getClient(SyncRequest $event): ShopifyClientInterface
    {
        return $this->manager->driver($event->driver);
    }

    /**
     * Sync the records returned from Shopify.
     *
     * @param LazyCollection $records
     *
     * @return void
     * @throws Throwable
     */
    protected function syncRecords(LazyCollection $records): void
    {
        $records->each(function (Fluent $record) {
            $this->syncRecord($record);
        });
    }

    /**
     * Sync a single record returned from Shopify.
     *
     * @param Fluent $record
     *
     * @return Model
     * @throws Throwable
     */
    protected function syncRecord(Fluent $record): Model
    {
        return $this->transformer->transform($record);
    }

    /**
     * Process the request.
     *
     * @param SyncRequest $event
     */
    abstract protected function process(SyncRequest $event): void;
}
