<?php
namespace Viserio\Queue\Tests\Connectors;

use Interop\Container\ContainerInterface;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Viserio\Contracts\Encryption\Encrypter as EncrypterContract;
use Viserio\Queue\{
    Jobs\RabbitMQJob,
    Connectors\RabbitMQQueue
};

class RabbitMQQueueTest extends \PHPUnit_Framework_TestCase
{
    use MockeryTrait;

    public function testPushProperlyPushesJobOnToRabbitMQ()
    {
        $encrypter = $this->mock(EncrypterContract::class);
        $encrypter->shouldReceive('encrypt');

        $channel = $this->mock(AMQPChannel::class);
        $channel->shouldReceive('exchange_declare')
            ->times(4)
            ->with('messages.exchange', 'direct', false, true, false);
        $channel->shouldReceive('queue_declare')
            ->twice()
            ->with('cnc', false, true, false, false);
        $channel->shouldReceive('queue_bind')
            ->twice()
            ->with('cnc', 'messages.exchange', 'cnc');
        $channel->shouldReceive('basic_publish')
            ->twice();
        $channel->shouldReceive('queue_declare')
            ->twice()
            ->with('stack', false, true, false, false);
        $channel->shouldReceive('queue_bind')
            ->twice()
            ->with('stack', 'messages.exchange', 'stack');

        $connection = $this->mock(AMQPStreamConnection::class);
        $connection->shouldReceive('channel')
            ->once()
            ->andReturn($channel);

        $queue = new RabbitMQQueue(
            $connection,
            [
                'queue' => 'cnc',
                'queue_params' => [
                    'passive' => false,
                    'durable' => true,
                    'exclusive' => false,
                    'auto_delete' => false,
                ],
                'exchange_params' => [
                    'name' => 'messages.exchange',
                    'type' => 'direct',
                    'passive' => false,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                'exchange_declare' => true,
                'queue_declare_bind' => 'cnc',
            ]
        );
        $queue->setEncrypter($encrypter);

        $queue->push('foo', ['someData']);
        $queue->push('foo', ['someData'], 'stack');
    }

    public function testDelayedPushProperlyPushesJobOnToRabbitMQ()
    {
        $encrypter = $this->mock(EncrypterContract::class);
        $encrypter->shouldReceive('encrypt');

        $channel = $this->mock(AMQPChannel::class);
        $channel->shouldReceive('exchange_declare')
            ->twice()
            ->with('messages.exchange', 'direct', false, true, false);
        $channel->shouldReceive('queue_declare')
            ->once()
            ->with('cnc', false, true, false, false);
        $channel->shouldReceive('queue_declare')
            ->once();
        $channel->shouldReceive('queue_bind')
            ->once()
            ->with('cnc', 'messages.exchange', 'cnc');
        $channel->shouldReceive('queue_bind')
            ->once()
            ->with('cnc_deferred_5', 'messages.exchange', 'cnc_deferred_5');
        $channel->shouldReceive('basic_publish')
            ->once();

        $connection = $this->mock(AMQPStreamConnection::class);
        $connection->shouldReceive('channel')
            ->once()
            ->andReturn($channel);

        $queue = new RabbitMQQueue(
            $connection,
            [
                'queue' => 'cnc',
                'queue_params' => [
                    'passive' => false,
                    'durable' => true,
                    'exclusive' => false,
                    'auto_delete' => false,
                ],
                'exchange_params' => [
                    'name' => 'messages.exchange',
                    'type' => 'direct',
                    'passive' => false,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                'exchange_declare' => true,
                'queue_declare_bind' => 'cnc',
            ]
        );
        $queue->setEncrypter($encrypter);

        $queue->later(5, 'foo', ['someData']);
    }

    public function testPopProperlyPopsJobOffOfBeanstalkd()
    {
        $job = $this->mock(Job::class);

        $encrypter = $this->mock(EncrypterContract::class);
        $encrypter->shouldReceive('encrypt');

        $channel = $this->mock(AMQPChannel::class);
        $channel->shouldReceive('exchange_declare')
            ->once()
            ->with('messages.exchange', 'direct', false, true, false);
        $channel->shouldReceive('queue_declare')
            ->once()
            ->with('cnc', false, true, false, false);
        $channel->shouldReceive('queue_bind')
            ->once()
            ->with('cnc', 'messages.exchange', 'cnc');
        $channel->shouldReceive('basic_get')
            ->once()
            ->with('cnc')
            ->andReturn($this->mock(AMQPMessage::class));

        $connection = $this->mock(AMQPStreamConnection::class);
        $connection->shouldReceive('channel')
            ->once()
            ->andReturn($channel);

        $queue = new RabbitMQQueue(
            $connection,
            [
                'queue' => 'cnc',
                'queue_params' => [
                    'passive' => false,
                    'durable' => true,
                    'exclusive' => false,
                    'auto_delete' => false,
                ],
                'exchange_params' => [
                    'name' => 'messages.exchange',
                    'type' => 'direct',
                    'passive' => false,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                'exchange_declare' => true,
                'queue_declare_bind' => 'cnc',
            ]
        );
        $queue->setEncrypter($encrypter);
        $queue->setContainer($this->mock(ContainerInterface::class));

        $result = $queue->pop();

        $this->assertInstanceOf(RabbitMQJob::class, $result);
    }
}
