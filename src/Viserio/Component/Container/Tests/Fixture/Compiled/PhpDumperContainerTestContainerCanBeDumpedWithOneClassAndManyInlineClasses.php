<?php

declare(strict_types=1);

namespace Viserio\Component\Container\Tests\IntegrationTest\Dumper\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class PhpDumperContainerTestContainerCanBeDumpedWithOneClassAndManyInlineClasses extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            \Viserio\Component\Container\Tests\Fixture\Inline\Class20::class => 'getb0ae4f4bd1139e25b0c2b392be3556a5ddfcedd781189261ea0046f7c6530a6f',
        ];
    }

    /**
     * Returns the public Viserio\Component\Container\Tests\Fixture\Inline\Class20 service.
     *
     * @return \Viserio\Component\Container\Tests\Fixture\Inline\Class20
     */
    protected function getb0ae4f4bd1139e25b0c2b392be3556a5ddfcedd781189261ea0046f7c6530a6f(): \Viserio\Component\Container\Tests\Fixture\Inline\Class20
    {
        return new \Viserio\Component\Container\Tests\Fixture\Inline\Class20(new \Viserio\Component\Container\Tests\Fixture\Inline\Class19(new \Viserio\Component\Container\Tests\Fixture\Inline\Class18(new \Viserio\Component\Container\Tests\Fixture\Inline\Class17(new \Viserio\Component\Container\Tests\Fixture\Inline\Class16(new \Viserio\Component\Container\Tests\Fixture\Inline\Class15(new \Viserio\Component\Container\Tests\Fixture\Inline\Class14(new \Viserio\Component\Container\Tests\Fixture\Inline\Class13(new \Viserio\Component\Container\Tests\Fixture\Inline\Class12(new \Viserio\Component\Container\Tests\Fixture\Inline\Class11(new \Viserio\Component\Container\Tests\Fixture\Inline\Class10(new \Viserio\Component\Container\Tests\Fixture\Inline\Class9(new \Viserio\Component\Container\Tests\Fixture\Inline\Class8(new \Viserio\Component\Container\Tests\Fixture\Inline\Class7(new \Viserio\Component\Container\Tests\Fixture\Inline\Class6(new \Viserio\Component\Container\Tests\Fixture\Inline\Class5(new \Viserio\Component\Container\Tests\Fixture\Inline\Class4(new \Viserio\Component\Container\Tests\Fixture\Inline\Class3(new \Viserio\Component\Container\Tests\Fixture\Inline\Class2(new \Viserio\Component\Container\Tests\Fixture\Inline\Class1())))))))))))))))))));
    }

    /**
     * {@inheritdoc}
     */
    public function getRemovedIds(): array
    {
        return [
            \Psr\Container\ContainerInterface::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class1::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class10::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class11::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class12::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class13::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class14::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class15::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class16::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class17::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class18::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class19::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class2::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class3::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class4::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class5::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class6::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class7::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class8::class => true,
            \Viserio\Component\Container\Tests\Fixture\Inline\Class9::class => true,
            \Viserio\Contract\Container\Factory::class => true,
            \Viserio\Contract\Container\TaggedContainer::class => true,
            'container' => true,
        ];
    }
}
