<?php
declare(strict_types=1);
namespace Viserio\View\Engines\Adapter;

use Exception;
use League\Plates\Engine as LeagueEngine;
use League\Plates\Extension\Asset;
use League\Plates\Extension\URI;
use League\Plates\Template\Template;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Viserio\Contracts\Config\Manager as ManagerContract;
use Viserio\Contracts\View\Engine as EnginesContract;

class Plates implements EnginesContract
{
    /**
     * Config manager instance.
     *
     * @var \Viserio\Contracts\Config\Manager
     */
    protected $config;

    /**
     * Engine instance.
     *
     * @var \League\Plates\Engine
     */
    protected $engine;

    /**
     * Server request instance.
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * All available extensions.
     *
     * @var array
     */
    protected $availableExtensions = [];

    /**
     * Create a new plates view instance.
     *
     * @param \Viserio\Contracts\Config\Manager             $config
     * @param \Psr\Http\Message\ServerRequestInterface|null $request
     */
    public function __construct(ManagerContract $config, ServerRequestInterface $request = null)
    {
        $this->config = $config;
        $this->request = $request;

        $exceptions = $this->config->get('view.engine.plates.extensions', null);

        if ($exceptions !== null) {
            $this->availableExtensions = $exceptions;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $fileInfo, array $data = []): string
    {
        $engine = $this->getLoader();

        if ($this->request !== null) {
            // Set uri extensions
            $engine->loadExtension(new URI($this->request->getUri()->getPath()));
        }

        // Set asset extensions
        $engine->loadExtension(new Asset($this->config->get('view.engine.plates.asset', null)));

        // Get all extensions
        if (! empty($this->availableExtensions)) {
            foreach ($this->availableExtensions as $extension) {
                $engine->loadExtension(is_object($extension) ? $extension : new $extension());
            }
        }

        if (! $engine->exists($fileInfo['name'])) {
            throw new Exception('Template "' . $fileInfo['name'] . '" dont exist!');
        }

        // Creat a new template
        $template = new Template($engine, $fileInfo['name']);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        ob_start();

        try {
            return $template->render($data);
        } catch (Throwable $exception) {
            $this->handleViewException($exception);
        }
    }

    /**
     * Plates paths loader.
     */
    protected function getLoader(): LeagueEngine
    {
        if (! $this->engine) {
            $config = $this->config;
            $this->engine = new LeagueEngine(
                $config->get('view.template.default', null),
                $config->get('view.engine.plates.file_extension', null)
            );

            if (($paths = $config->get('view.template.paths', null)) !== null) {
                foreach ($paths as $name => $addPaths) {
                    $this->engine->addFolder($name, $addPaths);
                }
            }
        }

        return $this->engine;
    }

    /**
     * Handle a view exception.
     *
     * @param \Throwable $exception
     *
     * @throws $exception
     */
    protected function handleViewException(Throwable $exception)
    {
        ob_get_clean();

        throw $exception;
    }
}