<?php
declare(strict_types=1);
namespace Viserio\Database\Proxies;

use Viserio\StaticalProxy\StaticalProxy;

class DB extends StaticalProxy
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public static function getInstanceIdentifier()
    {
        return 'db';
    }
}