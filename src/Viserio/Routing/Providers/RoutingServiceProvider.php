<?php
namespace Viserio\Routing\Providers;

/**
 * Narrowspark - a PHP 5 framework.
 *
 * @author      Daniel Bannert <info@anolilab.de>
 * @copyright   2015 Daniel Bannert
 *
 * @link        http://www.narrowspark.de
 *
 * @license     http://www.narrowspark.com/license
 *
 * @version     0.10.0-dev
 */

use FastRoute\DataGenerator\GroupCountBased;
use Viserio\Application\ServiceProvider;
use Viserio\Routing\RouteCollection;
use Viserio\Routing\RouteParser;
use Viserio\Routing\UrlGenerator\GroupCountBasedDataGenerator;
use Viserio\Routing\UrlGenerator\SimpleUrlGenerator;

/**
 * RoutingServiceProvider.
 *
 * @author  Daniel Bannert
 *
 * @since   0.9.4-dev
 */
class RoutingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('route', function ($app) {
            return new RouteCollection(
                $app,
                new RouteParser(),
                new GroupCountBased()
            );
        });

        $this->registerUrlGenerator();
    }

    protected function registerUrlGenerator()
    {
        $this->registerUrlGeneratorDataGenerator();

        $this->app->singleton('route.url.generator', function ($app) {
            return (new SimpleUrlGenerator($app->get('route.url.data.generator')))->setRequest($app['request']);
        });
    }

    protected function registerUrlGeneratorDataGenerator()
    {
        $this->app->singleton('route.url.data.generator', function ($app) {
            return new GroupCountBasedDataGenerator($app->get('route'));
        });
    }

    public function boot()
    {
        require $this->app->path().'/Http/routes.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'route',
            'route.url.generator',
            'route.url.data.generator',
        ];
    }
}