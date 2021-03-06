<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viserio\Component\Session\Tests\Handler;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Viserio\Component\Session\Handler\FileSessionHandler;

/**
 * @internal
 *
 * @small
 */
final class FileSessionHandlerTest extends TestCase
{
    /** @var \org\bovigo\vfs\vfsStreamDirectory */
    private $root;

    /** @var \Viserio\Component\Session\Handler\FileSessionHandler */
    private $handler;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->root = vfsStream::setup();
        $this->handler = new FileSessionHandler(
            $this->root->url(),
            60
        );
    }

    public function testOpenReturnsTrue(): void
    {
        self::assertTrue($this->handler->open($this->root->url(), 'temp'));
    }

    public function testCloseReturnsTrue(): void
    {
        self::assertTrue($this->handler->close());
    }

    public function testReadExistingSessionReturnsTheData(): void
    {
        vfsStream::newFile('temp.' . FileSessionHandler::FILE_EXTENSION)
            ->withContent('Foo Bar')
            ->at($this->root);

        self::assertSame('Foo Bar', $this->handler->read('temp'));
    }

    public function testReadMissingSessionReturnsAnEmptyString(): void
    {
        vfsStream::newFile('temp')
            ->withContent('Foo Bar')
            ->at($this->root);

        self::assertSame('', $this->handler->read('12'));
    }

    public function testWriteSuccessfullyReturnsTrue(): void
    {
        $dir = __DIR__ . \DIRECTORY_SEPARATOR . __FUNCTION__;

        \mkdir($dir);

        $handler = new FileSessionHandler($dir, 120);

        self::assertTrue($handler->write('write', \json_encode(['user_id' => 1])));

        \unlink($dir . \DIRECTORY_SEPARATOR . 'write.' . FileSessionHandler::FILE_EXTENSION);
        \rmdir($dir);
    }

    public function testGcSuccessfullyReturnsTrue(): void
    {
        $dir = __DIR__ . \DIRECTORY_SEPARATOR . __FUNCTION__;

        @\mkdir($dir);

        $handler = new FileSessionHandler($dir, 2);
        $handler->write('temp', \json_encode(['user_id' => 1]));

        self::assertSame('{"user_id":1}', $handler->read('temp'));

        \sleep(3);

        self::assertTrue($handler->gc(2));
        self::assertSame('', $handler->read('temp'));

        \rmdir($dir);
    }

    public function testDestroySuccessfullReturnsTrue(): void
    {
        vfsStream::newFile('destroy.' . FileSessionHandler::FILE_EXTENSION)
            ->withContent('Foo Bar')
            ->at($this->root);

        self::assertTrue($this->handler->destroy('destroy'));
    }

    public function testUpdateTimestamp(): void
    {
        $dir = __DIR__ . \DIRECTORY_SEPARATOR . __FUNCTION__;

        \mkdir($dir);

        $lifetime = 120;
        $handler = new FileSessionHandler($dir, $lifetime);

        $filePath = $dir . \DIRECTORY_SEPARATOR . 'update.' . FileSessionHandler::FILE_EXTENSION;

        $handler->write('update', \json_encode(['user_id' => 1]));

        self::assertTrue($handler->updateTimestamp('update', 'no'));

        \unlink($filePath);
        \rmdir($dir);
    }
}
