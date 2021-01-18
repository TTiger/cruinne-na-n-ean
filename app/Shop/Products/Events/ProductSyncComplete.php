<?php

declare(strict_types=1);

namespace App\Shop\Products\Events;

use Illuminate\Support\Carbon;

class ProductSyncComplete
{
    /**
     * @var Carbon
     */
    public Carbon $timestamp;

    /**
     * ProductSyncComplete constructor.
     *
     * @param Carbon $timestamp
     */
    public function __construct(Carbon $timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
