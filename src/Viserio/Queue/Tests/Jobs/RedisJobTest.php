<?php
declare(strict_types=1);
namespace Viserio\Queue\Tests\Jobs;

use Interop\Container\ContainerInterface;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use stdClass;
use Viserio\Queue\Connectors\RedisQueue;
use Viserio\Queue\Jobs\RedisJob;
use PHPUnit\Framework\TestCase;

class RedisJobTest extends TestCase
{
    use MockeryTrait;

    public function testReleaseProperlyReleasesJobOntoRedis()
    {
        $job = $this->getJob();
        $job->getRedisQueue()->shouldReceive('deleteAndRelease')
            ->once()
            ->with('default', json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 2]), 1);

        $job->release(1);
    }

    public function testRunProperlyCallsTheJobHandler()
    {
        $job = $this->getJob();
        $job->getContainer()->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn($handler = $this->mock(stdClass::class));

        $handler->shouldReceive('run')
            ->once()
            ->with($job, ['data']);

        $job->run();
    }

    public function testDeleteRemovesTheJobFromRedis()
    {
        $job = $this->getJob();
        $job->getRedisQueue()->shouldReceive('deleteReserved')
            ->once()
            ->with('default', json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 2]));

        $job->delete();
    }

    protected function getJob()
    {
        return new RedisJob(
            $this->mock(ContainerInterface::class),
            $this->mock(RedisQueue::class),
            json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 1]),
            json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 2]),
            'default'
        );
    }
}
