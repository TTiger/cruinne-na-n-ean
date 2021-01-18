<?php

declare(strict_types=1);

namespace App\Shop\Orders\Commands;

use App\Shop\Orders\Events\OrderSyncRequest;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;

use function compact;

class OrderSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:sync:orders 
        {--driver=rest}
        {--limit=250}
        {--status=any}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders from Shopify';

    /**
     * Execute the console command.
     *
     * @param Dispatcher $dispatcher
     *
     * @return int
     */
    public function handle(Dispatcher $dispatcher): int
    {
        $driver = $this->option('driver');
        $status = $this->option('status');
        $limit = (int) $this->option('limit');

        $dispatcher->dispatch(new OrderSyncRequest($driver, compact('status', 'limit')));

        $this->info("Order sync using {$driver} dispatched to queue worker");

        return 0;
    }
}
