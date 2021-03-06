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

namespace Viserio\Component\Filesystem\Watcher\Resource;

use RecursiveDirectoryIterator;
use SplFileInfo;
use Viserio\Component\Filesystem\Watcher\Event\FileChangeEvent;
use Viserio\Contract\Filesystem\Watcher\Resource as ResourceContract;

/**
 * @internal
 */
final class DirectoryResource implements ResourceContract
{
    /** @var string */
    private $dir;

    /** @var \Viserio\Component\Filesystem\Watcher\Resource\FileResource[] */
    private $files;

    /**
     * Create a new DirectoryResource instance.
     *
     * @param string $dir
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->files = $this->getFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @return \Viserio\Component\Filesystem\Watcher\Event\FileChangeEvent[]
     */
    public function detectChanges(): array
    {
        $events = [];
        $currentFiles = $this->getFiles();

        // Check if any files has been added
        foreach (\array_keys($currentFiles) as $path) {
            if (! isset($this->files[$path])) {
                $this->files = $currentFiles;

                $events[] = new FileChangeEvent($path, FileChangeEvent::FILE_CREATED);
            }
        }

        // Check if any files has been deleted
        foreach (\array_keys($this->files) as $file) {
            if (! isset($currentFiles[$file])) {
                $this->files = $currentFiles;

                $events[] = new FileChangeEvent($file, FileChangeEvent::FILE_DELETED);
            }
        }

        foreach ($this->files as $file) {
            if (\count($changes = $file->detectChanges()) !== 0) {
                foreach ($changes as $change) {
                    $events[] = $change;
                }
            }
        }

        return $events;
    }

    /**
     * Returns found files in directory.
     *
     * @return \Viserio\Component\Filesystem\Watcher\Resource\FileResource[]
     */
    private function getFiles(): array
    {
        $files = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveDirectoryIterator($this->dir, RecursiveDirectoryIterator::SKIP_DOTS) as $file) {
            $realpath = $file->getRealPath();

            $path = $realpath !== false ? $realpath : $file->getPathname();

            $files[$path] = new FileResource($path);
        }

        return $files;
    }
}
