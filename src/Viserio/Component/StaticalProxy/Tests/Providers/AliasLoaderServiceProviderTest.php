<?php
declare(strict_types=1);
namespace Viserio\Component\StaticalProxy\Tests\Providers;

use PHPUnit\Framework\TestCase;
use Viserio\Component\Container\Container;
use Viserio\Component\OptionsResolver\Providers\OptionsResolverServiceProvider;
use Viserio\Component\StaticalProxy\AliasLoader;
use Viserio\Component\StaticalProxy\Providers\AliasLoaderServiceProvider;

class AliasLoaderServiceProviderTest extends TestCase
{
    public function testProvider()
    {
        $container = new Container();
        $container->register(new OptionsResolverServiceProvider());
        $container->register(new AliasLoaderServiceProvider());

        $container->instance('config', [
            'viserio' => [
                'staticalproxy' => [
                    'aliases' => [],
                ],
            ],
        ]);

        self::assertInstanceOf(AliasLoader::class, $container->get(AliasLoader::class));
        self::assertInstanceOf(AliasLoader::class, $container->get('alias'));
    }
}
