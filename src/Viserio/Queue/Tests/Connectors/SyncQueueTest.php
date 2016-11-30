<?php
declare(strict_types=1);
namespace Viserio\Queue\Tests\Connectors;

use Exception;
use Interop\Container\ContainerInterface;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use Opis\Closure\SerializableClosure;
use stdClass;
use Viserio\Contracts\Encryption\Encrypter as EncrypterContract;
use Viserio\Queue\Connectors\SyncQueue;
use Viserio\Queue\Jobs\SyncJob;
use Viserio\Queue\QueueClosure;
use Viserio\Queue\Tests\Fixture\FailingSyncQueueHandler;
use Viserio\Queue\Tests\Fixture\SyncQueueHandler;

class SyncQueueTest extends \PHPUnit_Framework_TestCase
{
    use MockeryTrait;

    public function testPushShouldRunJobInstantly()
    {
        unset($_SERVER['__sync.test']);

        $sync = new SyncQueue();
        $closure = function ($job) {
            $_SERVER['__sync.test'] = true;

            $job->delete();
        };

        $events = $this->mock(stdClass::class);
        $events->shouldReceive('trigger');

        $encrypter = $this->mock(EncrypterContract::class);
        $encrypter->shouldReceive('encrypt')
            ->once();
        $encrypter->shouldReceive('decrypt')
            ->once()
            ->andReturn(serialize(new SerializableClosure($closure)));

        $container = $this->mock(ContainerInterface::class);
        $container->shouldReceive('has')
            ->with('events')
            ->times(4)
            ->andReturn(true);
        $container->shouldReceive('get')
            ->with('events')
            ->times(4)
            ->andReturn($events);
        $container->shouldReceive('get')
            ->with(QueueClosure::class)
            ->andReturn(new QueueClosure($encrypter));
        $container->shouldReceive('get')
            ->with('SyncQueueHandler')
            ->andReturn(new SyncQueueHandler());

        $sync->setContainer($container);
        $sync->setEncrypter($encrypter);
        $sync->push($closure);

        self::assertTrue($_SERVER['__sync.test']);

        unset($_SERVER['__sync.test']);

        $sync->push('SyncQueueHandler', ['foo' => 'bar']);

        self::assertInstanceOf(SyncJob::class, $_SERVER['__sync.test'][0]);
        self::assertEquals(['foo' => 'bar'], $_SERVER['__sync.test'][1]);
    }

    public function testFailedJobGetsHandledWhenAnExceptionIsThrown()
    {
        unset($_SERVER['__sync.failed']);

        $events = $this->mock(stdClass::class);
        $events->shouldReceive('trigger')
            ->times(3);

        $container = $this->mock(ContainerInterface::class);
        $container->shouldReceive('has')
            ->with('events')
            ->andReturn(true);
        $container->shouldReceive('get')
            ->with('events')
            ->andReturn($events);
        $container->shouldReceive('get')
            ->with('FailingSyncQueueHandler')
            ->andReturn(new FailingSyncQueueHandler());

        $encrypter = $this->mock(EncrypterContract::class);

        $sync = new SyncQueue();

        $sync->setContainer($container);
        $sync->setEncrypter($encrypter);

        try {
            $sync->push('FailingSyncQueueHandler', ['foo' => 'bar']);
        } catch (Exception $e) {
            self::assertTrue($_SERVER['__sync.failed']);
        }
    }
}
