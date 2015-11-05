<?php
namespace Brainwave\Application\Proxies;

/**
 * Narrowspark - a PHP 5 framework.
 *
 * @author      Daniel Bannert <info@anolilab.de>
 * @copyright   2015 Daniel Bannert
 *
 * @link        http://www.narrowspark.de
 *
 * @license     http://www.narrowspark.com/license
 *
 * @version     0.10.0-dev
 */

use Brainwave\Support\StaticalProxyManager;

/**
 * App.
 *
 * @author  Daniel Bannert
 *
 * @since   0.8.0-dev
 */
class App extends StaticalProxyManager
{
    protected static function getFacadeAccessor()
    {
        return self::$container;
    }

    public static function make($key)
    {
        return self::$container[$key];
    }
}
