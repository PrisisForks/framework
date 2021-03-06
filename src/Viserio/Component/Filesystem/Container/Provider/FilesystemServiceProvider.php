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

namespace Viserio\Component\Filesystem\Container\Provider;

use Viserio\Component\Container\Pipeline\ResolvePreloadPipe;
use Viserio\Component\Filesystem\Filesystem;
use Viserio\Contract\Container\ServiceProvider\AliasServiceProvider as AliasServiceProviderContract;
use Viserio\Contract\Container\ServiceProvider\ContainerBuilder as ContainerBuilderContract;
use Viserio\Contract\Container\ServiceProvider\ServiceProvider as ServiceProviderContract;
use Viserio\Contract\Filesystem\DirectorySystem as DirectorySystemContract;
use Viserio\Contract\Filesystem\Filesystem as FilesystemContract;
use Viserio\Contract\Filesystem\LinkSystem as LinkSystemContract;

class FilesystemServiceProvider implements AliasServiceProviderContract, ServiceProviderContract
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilderContract $container): void
    {
        $container->singleton(FilesystemContract::class, Filesystem::class)
            ->addTag(ResolvePreloadPipe::TAG);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): array
    {
        return [
            'files' => FilesystemContract::class,
            Filesystem::class => FilesystemContract::class,
            DirectorySystemContract::class => FilesystemContract::class,
            LinkSystemContract::class => FilesystemContract::class,
        ];
    }
}
