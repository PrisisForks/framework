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

namespace Viserio\Component\Config\Tests\Fixture;

use Viserio\Contract\OptionsResolver\ProvidesDefaultOptions as ProvidesDefaultOptionsContract;
use Viserio\Contract\OptionsResolver\RequiresComponentConfig as RequiresComponentConfigContract;

class ConnectionComponentDefaultOptionsConfiguration implements ProvidesDefaultOptionsContract, RequiresComponentConfigContract
{
    public static function getDimensions(): array
    {
        return ['doctrine', 'connection'];
    }

    public static function getDefaultOptions(): array
    {
        return [
            'params' => [
                'host' => 'awesomehost',
                'port' => '4444',
            ],
        ];
    }
}