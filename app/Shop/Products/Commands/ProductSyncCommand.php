<?php

declare(strict_types=1);

namespace App\Shop\Products\Commands;

use App\Shop\Products\Events\ProductSyncRequest;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;

use function compact;

class ProductSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:sync:products 
        {--driver=rest}
        {--limit=250}
        {--status=active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products from Shopify';

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

        $dispatcher->dispatch(new ProductSyncRequest($driver, compact('status', 'limit')));

        $this->info("Product sync using {$driver} dispatched to queue worker");

        return 0;
    }
}
