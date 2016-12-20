<?php
declare(strict_types=1);
namespace Viserio\Filesystem\Traits;

use Viserio\Contracts\Filesystem\Exception\FileNotFoundException;

trait FilesystemHelperTrait
{
    /**
     * Require file.
     *
     * @param string $path
     *
     * @throws Viserio\Contracts\Filesystem\Exception\FileNotFoundException
     *
     * @return mixed
     */
    public function getRequire(string $path)
    {
        $path = $this->getNormalzedOrPrefixedPath($path);

        if ($this->isFile($path) && $this->has($path)) {
            return require $path;
        }

        throw new FileNotFoundException($path);
    }

    /**
     * Require file once.
     *
     * @param string $path
     *
     * @throws Viserio\Contracts\Filesystem\Exception\FileNotFoundException
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function requireOnce(string $path)
    {
        $path = $this->getNormalzedOrPrefixedPath($path);

        if ($this->isFile($path) && $this->has($path)) {
            require_once $path;
        }

        throw new FileNotFoundException($path);
    }

    /**
     * Check if path is writable.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($this->getNormalzedOrPrefixedPath($path));
    }

    /**
     * Check if path is a file.
     *
     * @param string $file
     *
     * @return bool
     */
    public function isFile(string $file): bool
    {
        return is_file($this->getNormalzedOrPrefixedPath($file));
    }

    /**
     * Create a hard link to the target file or directory.
     *
     * @param string $target
     * @param string $link
     *
     * @return bool|null
     *
     * @codeCoverageIgnore
     */
    public function link(string $target, string $link)
    {
        $target = $this->getNormalzedOrPrefixedPath($target);
        $link   = $this->getNormalzedOrPrefixedPath($link);

        if (!$this->isWindows()) {
            return symlink($target, $link);
        }

        $mode = $this->isDirectory($target) ? 'J' : 'H';

        exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    abstract public function has(string $path): bool;

    /**
     * Determine if the given path is a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    abstract public function isDirectory(string $dirname): bool;

    /**
     * Fix directory separators for windows and linux.
     *
     * @param string|array $paths
     *
     * @return string|array
     */
    abstract protected function normalizeDirectorySeparator($paths);

    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    protected function isWindows(): bool
    {
        return mb_strtolower(mb_substr(PHP_OS, 0, 3)) === 'win';
    }

    /**
     * Get normalize or prefixed path.
     *
     * @param string $path
     *
     * @return string
     */
    abstract protected function getNormalzedOrPrefixedPath(string $path): string;
}
