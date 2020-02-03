<?php

declare(strict_types=1);

namespace Viserio\Component\Container\Tests\Integration\Dumper\Compiled;

/**
 * This class has been auto-generated by Viserio Container Component.
 */
final class PhpDumperContainerTestMultipleDeprecatedAliasesWorking extends \Viserio\Component\Container\AbstractCompiledContainer
{
    /**
     * Create a new Compiled Container instance.
     */
    public function __construct()
    {
        $this->services = $this->privates = [];
        $this->methodMapping = [
            'bar' => 'get91b123d3875702532e36683116824223d37b37377003156fc244abb2a82fec9c',
            'deprecated1' => 'get7531a655cc8348fa7f522ea58229f1d20f4cd74909f096fec007ff701166a532',
            'deprecated2' => 'get8b6311eb904beac221a430c7feeaa287758d4b97cec0486d6795eaab47f70108',
        ];
        $this->aliases = [
        ];
    }

    /**
     * Returns the public bar service.
     *
     * @return \stdClass
     */
    protected function get91b123d3875702532e36683116824223d37b37377003156fc244abb2a82fec9c(): \stdClass
    {
        return new \stdClass();
    }

    /**
     * Gets the public "deprecated1" alias.
     *
     * @return mixed The "bar" service.
     */
    protected function get7531a655cc8348fa7f522ea58229f1d20f4cd74909f096fec007ff701166a532()
    {
        @\trigger_error('The [deprecated1] service alias is deprecated. You should stop using it, as it will be removed in the future.', \E_USER_DEPRECATED);

        return $this->get('bar');
    }

    /**
     * Gets the public "deprecated2" alias.
     *
     * @return mixed The "bar" service.
     */
    protected function get8b6311eb904beac221a430c7feeaa287758d4b97cec0486d6795eaab47f70108()
    {
        @\trigger_error('The [deprecated2] service alias is deprecated. You should stop using it, as it will be removed in the future.', \E_USER_DEPRECATED);

        return $this->get('bar');
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
