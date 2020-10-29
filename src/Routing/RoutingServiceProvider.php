<?php

declare(strict_types=1);

namespace FakeFakers\ApiComponents\Routing;

use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

/**
 * Class RoutingServiceProvider
 * @package FakeFakers\ApiComponents\Routing
 *
 * Created to hook laravel router
 */
class RoutingServiceProvider extends ServiceProvider
{
    /**
     * JSON encode options
     */
    public const JSON_OPTIONS = \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_LINE_TERMINATORS;

    /**
     * Hook router
     */
    protected function registerRouter(): void
    {
        $this->app->singleton('router', function ($app) {
            return new Router($app['events'], $app);
        });
    }

    /**
     * Hook response factory
     */
    protected function registerResponseFactory(): void
    {
        $this->app->singleton(ResponseFactoryContract::class, function ($app) {
            return new ResponseFactory($app[ViewFactoryContract::class], $app['redirect']);
        });
    }
}