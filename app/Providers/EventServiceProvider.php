<?php

declare(strict_types=1);

namespace App\Providers;

use App\Shop\Orders\Events\OrderSyncRequest;
use App\Shop\Orders\Listeners\SyncOrders;
use App\Shop\Products\Events\ProductSyncComplete;
use App\Shop\Products\Events\ProductSyncRequest;
use App\Shop\Products\Listeners\LogCompletion;
use App\Shop\Products\Listeners\SyncProducts;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductSyncRequest::class => [
            SyncProducts::class
        ],
        ProductSyncComplete::class => [
            LogCompletion::class
        ],
        OrderSyncRequest::class => [
            SyncOrders::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
