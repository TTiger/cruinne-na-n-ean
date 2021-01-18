<?php

declare(strict_types=1);

namespace App\Support\Shopify;

abstract class SyncRequest
{
    /**
     * @var string
     */
    public string $driver;

    /**
     * @var array
     */
    public array $params;

    /**
     * ProductSyncRequest constructor.
     *
     * @param string $driver
     * @param array $params
     */
    public function __construct(string $driver, array $params)
    {
        $this->driver = $driver;
        $this->params = $params;
    }
}
