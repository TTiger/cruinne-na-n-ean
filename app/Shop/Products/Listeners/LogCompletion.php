<?php

declare(strict_types=1);

namespace App\Shop\Products\Listeners;

use App\Shop\Products\Events\ProductSyncComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Logger;

class LogCompletion implements ShouldQueue
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * SyncProducts constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle the product synchronization request.
     *
     * @param ProductSyncComplete $event
     *
     * @return void
     */
    public function handle(ProductSyncComplete $event): void
    {
        $this->logger->info('Product sync completed at ' . $event->timestamp->toDateTimeString());
    }
}
