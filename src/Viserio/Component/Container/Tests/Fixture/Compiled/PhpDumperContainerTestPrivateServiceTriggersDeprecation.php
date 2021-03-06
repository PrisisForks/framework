<?php

declare(strict_types=1);

namespace Viserio\Component\Container\Tests\Integration\Dumper\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class PhpDumperContainerTestPrivateServiceTriggersDeprecation extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            'bar' => 'get91b123d3875702532e36683116824223d37b37377003156fc244abb2a82fec9c',
        ];
    }

    /**
     * Returns the public bar shared service.
     *
     * @return \stdClass
     */
    protected function get91b123d3875702532e36683116824223d37b37377003156fc244abb2a82fec9c(): \stdClass
    {
        $this->services['bar'] = $instance = new \stdClass();

        $instance->foo = $this->get55df4251026261c15e5362b72748729c5413605491a6b31caf07b0571c04af5f();

        return $instance;
    }

    /**
     * Returns the private foo shared service.
     *
     * @return \stdClass
     *
     * @deprecated The [foo] service is deprecated. You should stop using it, as it will be removed in the future.
     */
    protected function get55df4251026261c15e5362b72748729c5413605491a6b31caf07b0571c04af5f(): \stdClass
    {
        @\trigger_error('The [foo] service is deprecated. You should stop using it, as it will be removed in the future.', \E_USER_DEPRECATED);

        return new \stdClass();
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
            'foo' => true,
        ];
    }
}
