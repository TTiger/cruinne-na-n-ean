<?php

declare(strict_types=1);

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Application extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Support\Application::class;
    }
}