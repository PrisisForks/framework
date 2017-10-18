<?php
declare(strict_types=1);
namespace Viserio\Component\OptionsResolver\Tests\Fixtures\Options;

use Exception;
use Viserio\Component\Contract\OptionsResolver\ProvidesDefaultOptions as ProvidesDefaultOptionsContract;
use Viserio\Component\Contract\OptionsResolver\RequiresComponentConfig as RequiresComponentConfigContract;
use Viserio\Component\Contract\OptionsResolver\RequiresMandatoryOptions as RequiresMandatoryOptionsContract;
use Viserio\Component\Contract\OptionsResolver\RequiresValidatedConfig as RequiresValidatedConfigContract;

class OptionsConfiguration implements RequiresComponentConfigContract, ProvidesDefaultOptionsContract, RequiresValidatedConfigContract, RequiresMandatoryOptionsContract
{
    /**
     * {@inheritdoc}.
     */
    public static function getDimensions(): iterable
    {
        return ['vendor', 'package'];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getDefaultOptions(): array
    {
        return [
            'minLength' => 2,
        ];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getMandatoryOptions(): iterable
    {
        return ['maxLength'];
    }

    /**
     * {@inheritdoc}.
     */
    public static function getOptionValidators(): array
    {
        return [
            'minLength' => function ($value): void {
                throw new Exception('Dont throw exception on default values');
            },
            'maxLength' => function ($value): void {
                if (! \is_int($value)) {
                    throw new Exception('Value is not a int.');
                }
            },
        ];
    }
}
