<?php
declare(strict_types=1);
namespace Viserio\Queue\Tests;

use Interop\Container\ContainerInterface as ContainerInteropInterface;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use Viserio\Contracts\Config\Repository as RepositoryContract;
use Viserio\Contracts\Encryption\Encrypter as EncrypterContract;
use Viserio\Queue\QueueManager;
use Viserio\Queue\Tests\Fixture\TestQueue;
use PHPUnit\Framework\TestCase;

class QueueManagerTest extends TestCase
{
    use MockeryTrait;

    public function testConnection()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('get')
            ->once()
            ->with('queue.connections', [])
            ->andReturn([]);

        $manager = new QueueManager(
            $config,
            $this->mock(ContainerInteropInterface::class),
            $this->mock(EncrypterContract::class)
        );

        $manager->extend('testqueue', function ($config) {
            return new TestQueue();
        });

        $connection = $manager->connection('testqueue');

        self::assertInstanceOf(ContainerInteropInterface::class, $connection->getContainer());
        self::assertInstanceOf(EncrypterContract::class, $connection->getEncrypter());
    }

    public function testSetAndGetEncrypter()
    {
        $config = $this->mock(RepositoryContract::class);

        $manager = new QueueManager(
            $this->mock(RepositoryContract::class),
            $this->mock(ContainerInteropInterface::class),
            $this->mock(EncrypterContract::class)
        );

        self::assertInstanceOf(EncrypterContract::class, $manager->getEncrypter());

        $manager->setEncrypter($this->mock(EncrypterContract::class));

        self::assertInstanceOf(EncrypterContract::class, $manager->getEncrypter());
    }
}
