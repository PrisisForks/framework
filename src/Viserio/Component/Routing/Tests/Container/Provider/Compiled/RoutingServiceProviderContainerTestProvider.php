<?php

declare(strict_types=1);

namespace Viserio\Component\Routing\Tests\Container\Provider\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class RoutingServiceProviderContainerTestProvider extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            \Viserio\Contract\Routing\Dispatcher::class => 'get584908ff464e7559233910f9ef37cbbc81593674d92ff5b6e814b73127f8e05c',
            \Viserio\Contract\Routing\Router::class => 'get410eb27931e780eeccc23e52a6c17e0e6e2e1827d28f90c4254c8f4111788d4e',
            \Viserio\Contract\Routing\UrlGenerator::class => 'getc9f84ddda9d00c1b7a3d422c26b92c3f7acb8604c9d7c3c5ca1e13e1367f8e4a',
        ];
        $this->uninitializedServices = [
            \Psr\Http\Message\ServerRequestInterface::class => true,
        ];
        $this->aliases = [
            \Viserio\Component\Routing\Generator\UrlGenerator::class => \Viserio\Contract\Routing\UrlGenerator::class,
            \Viserio\Component\Routing\Router::class => \Viserio\Contract\Routing\Router::class,
            'route' => \Viserio\Contract\Routing\Router::class,
            'router' => \Viserio\Contract\Routing\Router::class,
        ];
        $this->syntheticIds = [
            \Psr\Http\Message\ServerRequestInterface::class => true,
            \Psr\Http\Message\UriFactoryInterface::class => true,
        ];
    }

    /**
     * Returns the public Viserio\Contract\Routing\Dispatcher shared service.
     *
     * @return \Viserio\Component\Routing\Dispatcher\MiddlewareBasedDispatcher
     */
    protected function get584908ff464e7559233910f9ef37cbbc81593674d92ff5b6e814b73127f8e05c(): \Viserio\Component\Routing\Dispatcher\MiddlewareBasedDispatcher
    {
        return $this->services[\Viserio\Contract\Routing\Dispatcher::class] = new \Viserio\Component\Routing\Dispatcher\MiddlewareBasedDispatcher();
    }

    /**
     * Returns the public Viserio\Contract\Routing\Router shared service.
     *
     * @return \Viserio\Component\Routing\Router
     */
    protected function get410eb27931e780eeccc23e52a6c17e0e6e2e1827d28f90c4254c8f4111788d4e(): \Viserio\Component\Routing\Router
    {
        $this->services[\Viserio\Contract\Routing\Router::class] = $instance = new \Viserio\Component\Routing\Router(($this->services[\Viserio\Contract\Routing\Dispatcher::class] ?? $this->get584908ff464e7559233910f9ef37cbbc81593674d92ff5b6e814b73127f8e05c()));

        $instance->setContainer($this);

        return $instance;
    }

    /**
     * Returns the public Viserio\Contract\Routing\UrlGenerator shared service.
     *
     * @return \Viserio\Component\Routing\Generator\UrlGenerator
     */
    protected function getc9f84ddda9d00c1b7a3d422c26b92c3f7acb8604c9d7c3c5ca1e13e1367f8e4a(): \Viserio\Component\Routing\Generator\UrlGenerator
    {
        return $this->services[\Viserio\Contract\Routing\UrlGenerator::class] = new \Viserio\Component\Routing\Generator\UrlGenerator(($this->services[\Viserio\Contract\Routing\Router::class] ?? $this->get410eb27931e780eeccc23e52a6c17e0e6e2e1827d28f90c4254c8f4111788d4e())->getRoutes(), ($this->services[\Psr\Http\Message\ServerRequestInterface::class] ?? $this->get(\Psr\Http\Message\ServerRequestInterface::class)), ($this->services[\Psr\Http\Message\UriFactoryInterface::class] ?? $this->get(\Psr\Http\Message\UriFactoryInterface::class)));
    }

    /**
     * {@inheritdoc}
     */
    public function getRemovedIds(): array
    {
        return [
            \Psr\Container\ContainerInterface::class => true,
            \Viserio\Contract\Container\CompiledContainer::class => true,
            \Viserio\Contract\Container\Factory::class => true,
            \Viserio\Contract\Container\TaggedContainer::class => true,
            'container' => true,
        ];
    }
}
