<?php

declare(strict_types=1);

namespace Viserio\Component\Container\Tests\Integration\Dumper\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class PhpDumperContainerTestContainerCanBeDumpedWithWither extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            'wither' => 'getb1f36be5a182c547a6495f1016a20afdfddb5051ab023f3a6e6e27989bb03785',
        ];
    }

    /**
     * Returns the public wither shared service.
     *
     * @return \Viserio\Component\Container\Tests\Fixture\Wither
     */
    protected function getb1f36be5a182c547a6495f1016a20afdfddb5051ab023f3a6e6e27989bb03785(): \Viserio\Component\Container\Tests\Fixture\Wither
    {
        $instance = new \Viserio\Component\Container\Tests\Fixture\Wither();

        $a = new \Viserio\Component\Container\Tests\Fixture\EmptyClass();

        $instance = $instance->withEmptyClass1($a);
        $this->services['wither'] = $instance = $instance->withEmptyClass2($a);
        $instance->setEmptyClass($a);

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function getRemovedIds(): array
    {
        return [
            \Psr\Container\ContainerInterface::class => true,
            \Viserio\Component\Container\Tests\Fixture\EmptyClass::class => true,
            \Viserio\Contract\Container\CompiledContainer::class => true,
            \Viserio\Contract\Container\Factory::class => true,
            \Viserio\Contract\Container\TaggedContainer::class => true,
            'container' => true,
        ];
    }
}
