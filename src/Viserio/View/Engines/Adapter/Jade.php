<?php
namespace Viserio\View\Engines\Adapter;

use Viserio\Contracts\View\Engine as EnginesContract;

/**
 * Jade.
 *
 * @author  Daniel Bannert
 *
 * @since   0.9.5
 */
class Jade implements EnginesContract
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    public function get($path, array $data = [])
    {
        //TODO
    }
}
