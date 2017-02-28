<?php
declare(strict_types=1);
namespace Viserio\Component\Mail\Tests;

use Narrowspark\TestingHelper\ArrayContainer;
use Narrowspark\TestingHelper\Phpunit\MockeryTestCase;
use Psr\Log\LoggerInterface;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Viserio\Component\Contracts\Config\Repository as RepositoryContract;
use Viserio\Component\Mail\Transport\ArrayTransport;
use Viserio\Component\Mail\Transport\LogTransport;
use Viserio\Component\Mail\Transport\MailgunTransport;
use Viserio\Component\Mail\Transport\MandrillTransport;
use Viserio\Component\Mail\Transport\SesTransport;
use Viserio\Component\Mail\Transport\SparkPostTransport;
use Viserio\Component\Mail\TransportManager;

class TransportManagerTest extends MockeryTestCase
{
    public function testLogTransporter()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));
        $manager->setContainer(new ArrayContainer([
            LoggerInterface::class    => $this->mock(LoggerInterface::class),
        ]));

        self::assertInstanceOf(LogTransport::class, $manager->getDriver('log'));
    }

    public function testSendmailTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(Swift_SendmailTransport::class, $manager->getDriver('sendmail'));
    }

    public function testSmtpTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [
                        'smtp' => [
                            'host'       => '',
                            'port'       => '',
                            'encryption' => '',
                            'username'   => '',
                            'password'   => '',
                            'stream'     => '',
                        ],
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(Swift_SmtpTransport::class, $manager->getDriver('smtp'));
    }

    public function testMailgunTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [
                        'mailgun' => [
                            'secret' => '',
                            'domain' => '',
                        ],
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(MailgunTransport::class, $manager->getDriver('mailgun'));
    }

    public function testMandrillTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [
                        'mandrill' => [
                            'secret' => '',
                        ],
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(MandrillTransport::class, $manager->getDriver('mandrill'));
    }

    public function testSparkPostTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [
                        'sparkpost' => [
                            'secret' => '',
                        ],
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(SparkPostTransport::class, $manager->getDriver('sparkpost'));
    }

    public function testSesTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'   => [
                        'ses' => [
                            'secret' => '',
                            'key'    => '',
                            'region' => 'us-west-2',
                        ],
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(SesTransport::class, $manager->getDriver('ses'));
    }

    public function testArrayTransport()
    {
        $config = $this->mock(RepositoryContract::class);
        $config->shouldReceive('offsetExists')
            ->once()
            ->with('viserio')
            ->andReturn(true);
        $config->shouldReceive('offsetGet')
            ->once()
            ->with('viserio')
            ->andReturn([
                'mail' => [
                    'drivers'=> [
                    ],
                ],
            ]);

        $manager = new TransportManager(new ArrayContainer([
            RepositoryContract::class => $config,
        ]));

        self::assertInstanceOf(ArrayTransport::class, $manager->getDriver('local'));
    }
}