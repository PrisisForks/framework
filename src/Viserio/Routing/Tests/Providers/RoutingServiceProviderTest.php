<?php
declare(strict_types=1);
namespace Viserio\Routing\Tests\Providers;

use Viserio\Container\Container;
use Viserio\Events\Providers\EventsServiceProvider;
use Viserio\Routing\Providers\RoutingServiceProvider;
use Viserio\Routing\Router;

class RoutingServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testProvider()
    {
        $container = new Container();
        $container->register(new RoutingServiceProvider());
        $container->register(new EventsServiceProvider());

        self::assertInstanceOf(Router::class, $container->get(Router::class));
        self::assertInstanceOf(Router::class, $container->get('router'));
    }
}
