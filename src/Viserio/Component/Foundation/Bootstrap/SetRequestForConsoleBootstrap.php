<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viserio\Component\Foundation\Bootstrap;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Viserio\Component\Config\Container\Definition\ConfigDefinition;
use Viserio\Component\Container\Definition\ReferenceDefinition;
use Viserio\Contract\Config\RequiresComponentConfig as RequiresComponentConfigContract;
use Viserio\Contract\Config\RequiresMandatoryConfig as RequiresMandatoryConfigContract;
use Viserio\Contract\Config\RequiresValidatedConfig as RequiresValidatedConfigContract;
use Viserio\Contract\Foundation\Bootstrap as BootstrapContract;
use Viserio\Contract\Foundation\Kernel as KernelContract;

class SetRequestForConsoleBootstrap implements BootstrapContract,
    RequiresComponentConfigContract,
    RequiresMandatoryConfigContract,
    RequiresValidatedConfigContract
{
    /**
     * {@inheritdoc}
     */
    public static function getDimensions(): iterable
    {
        return ['viserio', 'app'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getMandatoryConfig(): iterable
    {
        return [
            'url',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getConfigValidators(): iterable
    {
        return [
            'url' => ['string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getPriority(): int
    {
        return 128;
    }

    /**
     * {@inheritdoc}
     */
    public static function isSupported(KernelContract $kernel): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function bootstrap(KernelContract $kernel): void
    {
        if (! \interface_exists(ServerRequestFactoryInterface::class)) {
            return;
        }

        $containerBuilder = $kernel->getContainerBuilder();

        $containerBuilder->singleton(ServerRequestInterface::class, [new ReferenceDefinition(ServerRequestFactoryInterface::class, ReferenceDefinition::IGNORE_ON_INVALID_REFERENCE), 'createServerRequest'])
            ->setArguments([
                'GET',
                (new ConfigDefinition(self::class))->setKey('url'),
            ]);
    }
}
