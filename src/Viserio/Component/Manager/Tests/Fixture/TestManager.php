<?php
declare(strict_types=1);
namespace Viserio\Component\Manager\Tests\Fixture;

use stdClass;
use Viserio\Component\Manager\AbstractManager;

class TestManager extends AbstractManager
{
    protected function createTestDriver($config = null)
    {
        return true;
    }

    protected function createConfigDriver($config)
    {
        return $config;
    }

    protected function createValueDriver($config)
    {
        return $config;
    }

    protected function createTestmanagerDriver($config)
    {
        return new stdClass();
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected static function getConfigName(): string
    {
        return 'test';
    }
}