<?php
declare(strict_types=1);
namespace Viserio\Component\Contract\View;

use Viserio\Component\Contract\Support\Renderable;

interface View extends Renderable
{
    /**
     * Get the name of the view.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the array of view data.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Get the path to the view file.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set the path to the view.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): View;

    /**
     * Add a piece of data to the view.
     *
     * @param array|string $key
     * @param mixed        $value
     *
     * @return $this
     */
    public function with($key, $value = null): View;

    /**
     * Get the string contents of the view.
     *
     * @param null|callable $callback
     *
     * @return string
     */
    public function render(callable $callback = null): string;

    /**
     * Add a view instance to the view data.
     *
     * @param string   $key
     * @param string   $view
     * @param string[] $data
     *
     * @return $this
     */
    public function nest($key, string $view, array $data = []): View;

    /**
     * Get the view factory instance.
     *
     * @return \Viserio\Component\Contract\View\Factory
     */
    public function getFactory(): Factory;

    /**
     * Get the view's rendering engine.
     *
     * @return \Viserio\Component\Contract\View\Engine
     */
    public function getEngine(): Engine;
}