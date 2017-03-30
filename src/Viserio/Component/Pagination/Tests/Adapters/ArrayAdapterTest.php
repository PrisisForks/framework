<?php
declare(strict_types=1);
namespace Viserio\Component\Pagination\Tests\Adapters;

use PHPUnit\Framework\TestCase;
use Viserio\Component\Pagination\Adapters\ArrayAdapter;

class ArrayAdapterTest extends TestCase
{
    public function testGetItems()
    {
        $adapter = new ArrayAdapter(['test'], 1);

        self::assertSame(['test'], $adapter->getItems());
    }

    public function testGetItemsPerPage()
    {
        $adapter = new ArrayAdapter(['test'], 1);

        self::assertSame(1, $adapter->getItemsPerPage());
    }
}
