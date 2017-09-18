<?php
declare(strict_types=1);
namespace Viserio\Component\Parser\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Viserio\Component\Parser\GroupParser;

class GroupParserTest extends TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    private $root;

    /**
     * @var \Viserio\Component\Parser\GroupParser
     */
    private $parser;

    public function setUp(): void
    {
        $this->root   = vfsStream::setup();
        $this->parser = new GroupParser();
    }

    public function testParse(): void
    {
        $file = vfsStream::newFile('temp.json')->withContent(
            '
{
    "a":1,
    "e":5
}
            '
        )->at($this->root);

        $parsed = $this->parser->parse($file->url());

        self::assertTrue(\is_array($parsed));
        self::assertSame(['a' => 1, 'e' => 5], $parsed);
    }

    public function testParseTag(): void
    {
        $file = vfsStream::newFile('temp.json')->withContent(
            '
{
    "a":1,
    "e":5
}
            '
        )->at($this->root);

        $parsed = $this->parser->setGroup('foo')->parse($file->url());

        self::assertTrue(\is_array($parsed));
        self::assertSame(['foo' => ['a' => 1, 'e' => 5]], $parsed);
    }
}