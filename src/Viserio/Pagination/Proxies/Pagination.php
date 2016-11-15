<?php
declare(strict_types=1);
namespace Viserio\Pagination\Proxies;

use Viserio\StaticalProxy\StaticalProxy;

class Pagination extends StaticalProxy
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public static function getInstanceIdentifier()
    {
        return 'pagination';
    }
}
