<?php

declare(strict_types=1);

namespace Viserio\Component\Container\Tests\Integration\Dumper\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class PhpDumperContainerTestContainerCanBeDumpedWithInvokeParameterAndConstructorParameterClass extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            'foo' => 'get55df4251026261c15e5362b72748729c5413605491a6b31caf07b0571c04af5f',
        ];
    }

    /**
     * Returns the public foo shared service.
     *
     * @return mixed An instance returned by \Viserio\Component\Container\Tests\Fixture\Invoke\InvokeParameterAndConstructorParameterClass::__invoke()
     */
    protected function get55df4251026261c15e5362b72748729c5413605491a6b31caf07b0571c04af5f()
    {
        $a = new \Viserio\Component\Container\Tests\Fixture\EmptyClass();

        return $this->services['foo'] = (new \Viserio\Component\Container\Tests\Fixture\Invoke\InvokeParameterAndConstructorParameterClass($a))->__invoke(new \Viserio\Component\Container\Tests\Fixture\Invoke\InvokeCallableClass());
    }

    /**
     * {@inheritdoc}
     */
    public function getRemovedIds(): array
    {
        return [
            \Psr\Container\ContainerInterface::class => true,
            \Viserio\Component\Container\Tests\Fixture\EmptyClass::class => true,
            \Viserio\Component\Container\Tests\Fixture\Invoke\InvokeCallableClass::class => true,
            \Viserio\Contract\Container\CompiledContainer::class => true,
            \Viserio\Contract\Container\Factory::class => true,
            \Viserio\Contract\Container\TaggedContainer::class => true,
            'container' => true,
        ];
    }
}
