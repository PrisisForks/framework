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

namespace Viserio\Component\OptionsResolver\Tests\Fixture;

use Viserio\Contract\OptionsResolver\DeprecatedOptions as DeprecatedOptionsContract;
use Viserio\Contract\OptionsResolver\ProvidesDefaultOption as ProvidesDefaultOptionContract;
use Viserio\Contract\OptionsResolver\RequiresComponentConfigId as RequiresComponentConfigIdContract;
use Viserio\Contract\OptionsResolver\RequiresMandatoryOption as RequiresMandatoryOptionContract;

class ConnectionComponentDefaultOptionMandatoryContainedIdWithDeprecationKeyConfiguration implements DeprecatedOptionsContract, ProvidesDefaultOptionContract, RequiresComponentConfigIdContract, RequiresMandatoryOptionContract
{
    /**
     * {@inheritdoc}.
     */
    public static function getDimensions(): array
    {
        return ['doctrine', 'connection'];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getMandatoryOptions(): array
    {
        return ['driverClass'];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getDefaultOptions(): array
    {
        return [
            'params' => [
                'host' => 'awesomehost',
                'port' => '4444',
            ],
        ];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getDeprecatedOptions(): array
    {
        return [
            'driverClass',
        ];
    }
}