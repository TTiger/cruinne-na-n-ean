<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\Application;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application as FrameworkApplication;
use Mockery;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_the_application_name(): void
    {
        $name = 'Universe of Birds';

        $config = Mockery::mock(Repository::class, static function ($mock) use ($name) {
            $mock->shouldReceive('get')->once()->with('app.name')->andReturn($name);
        });

        $mock = Mockery::mock(FrameworkApplication::class, static function ($mock) use ($config) {
            $mock->config = $config;
        });

        $application = new Application($mock);

        self::assertEquals('Universe of Birds', $application->name());
    }

    /**
     * @test
     */
    public function it_provides_the_application_language(): void
    {
        $language = 'fr';

        $mock = Mockery::mock(FrameworkApplication::class, static function ($mock) use ($language) {
            $mock->shouldReceive('getLocale')->once()->andReturn($language);
        });

        $application = new Application($mock);

        self::assertEquals('fr', $application->lang());
    }

    /**
     * @test
     */
    public function it_provides_the_application_language_including_locale(): void
    {
        $language = 'fr_CA';

        $mock = Mockery::mock(FrameworkApplication::class, static function ($mock) use ($language) {
            $mock->shouldReceive('getLocale')->once()->andReturn($language);
        });

        $application = new Application($mock);

        self::assertEquals('fr-CA', $application->lang());
    }

    /**
     * @test
     */
    public function it_provides_the_application_version(): void
    {
        $mock = Mockery::mock(FrameworkApplication::class);

        $application = new Application($mock);

        self::assertEquals('0.0.1', $application->version());
    }
}
