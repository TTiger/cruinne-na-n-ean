<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Foundation\Application as FrameworkApplication;

class Application
{
    private const VERSION = '0.0.1';

    /**
     * @var FrameworkApplication
     */
    private $app;

    /**
     * Application constructor.
     *
     * @param FrameworkApplication $app
     */
    public function __construct(FrameworkApplication $app)
    {
        $this->app = $app;
    }

    /**
     * The application name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->app->config->get('app.name');
    }

    /**
     * The application language.
     *
     * @return string
     */
    public function lang(): string
    {
        return str_replace('_', '-', $this->app->getLocale());
    }

    /**
     * The application version.
     *
     * @return string
     */
    public function version(): string
    {
        return self::VERSION;
    }
}
