<?php
declare(strict_types=1);
namespace Viserio\Component\Filesystem\Tests\Traits;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Viserio\Component\Filesystem\Traits\FilesystemExtensionTrait;
use Viserio\Component\Support\Traits\NormalizePathAndDirectorySeparatorTrait;

class FilesystemExtensionTraitTest extends TestCase
{
    use NormalizePathAndDirectorySeparatorTrait;
    use FilesystemExtensionTrait;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    private $root;

    /**
     * Setup the environment.
     */
    public function setUp()
    {
        $this->root = vfsStream::setup();
    }

    public function testWithoutExtension()
    {
        $file = vfsStream::newFile('temp.txt')->withContent('Foo Bar')->at($this->root);

        self::assertSame('temp', $this->withoutExtension($file->url(), 'txt'));

        $file = vfsStream::newFile('temp.php')->withContent('Foo Bar')->at($this->root);

        self::assertSame('temp', $this->withoutExtension($file->url()));
    }

    public function testGetExtensionReturnsExtension()
    {
        $file = vfsStream::newFile('rock.csv')->withContent('pop,rock')->at($this->root);

        self::assertEquals('csv', $this->getExtension($file->url()));
    }

    public function testChangeExtension()
    {
        $file = vfsStream::newFile('temp.txt')->withContent('Foo Bar')->at($this->root);

        self::assertSame(vfsStream::url('root/temp.php'), $this->changeExtension($file->url(), 'php'));

        $file = vfsStream::newFile('temp2')->withContent('Foo Bar')->at($this->root);

        self::assertSame(vfsStream::url('root/temp2.php'), $this->changeExtension($file->url(), 'php'));

        self::assertSame(vfsStream::url('root/temp3/'), $this->changeExtension(vfsStream::url('root/temp3/'), 'php'));
    }

    /**
     * Get normalize or prefixed path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getNormalizedOrPrefixedPath(string $path): string
    {
        if (isset($this->driver)) {
            $prefix = method_exists($this->driver, 'getPathPrefix') ? $this->driver->getPathPrefix() : '';

            return $prefix . $path;
        }

        return self::normalizeDirectorySeparator($path);
    }
}