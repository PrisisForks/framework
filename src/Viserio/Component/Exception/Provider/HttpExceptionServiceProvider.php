<?php
declare(strict_types=1);
namespace Viserio\Component\Exception\Provider;

use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Viserio\Component\Contract\Exception\HttpHandler as HttpHandlerContract;
use Viserio\Component\Contract\View\Factory as FactoryContract;
use Viserio\Component\Exception\Displayer\HtmlDisplayer;
use Viserio\Component\Exception\Displayer\JsonApiDisplayer;
use Viserio\Component\Exception\Displayer\JsonDisplayer;
use Viserio\Component\Exception\Displayer\SymfonyDisplayer;
use Viserio\Component\Exception\Displayer\ViewDisplayer;
use Viserio\Component\Exception\Displayer\WhoopsJsonDisplayer;
use Viserio\Component\Exception\Displayer\WhoopsPrettyDisplayer;
use Viserio\Component\Exception\Filter\CanDisplayFilter;
use Viserio\Component\Exception\Filter\ContentTypeFilter;
use Viserio\Component\Exception\Filter\VerboseFilter;
use Viserio\Component\Exception\Http\Handler;
use Whoops\Run;

class HttpExceptionServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFactories(): array
    {
        return [
            HtmlDisplayer::class         => [self::class, 'createHtmlDisplayer'],
            JsonDisplayer::class         => [self::class, 'createJsonDisplayer'],
            JsonApiDisplayer::class      => [self::class, 'createJsonApiDisplayer'],
            SymfonyDisplayer::class      => [self::class, 'createSymfonyDisplayer'],
            ViewDisplayer::class         => [self::class, 'createViewDisplayer'],
            WhoopsPrettyDisplayer::class => [self::class, 'createWhoopsPrettyDisplayer'],
            WhoopsJsonDisplayer::class   => [self::class, 'createWhoopsJsonDisplayer'],
            VerboseFilter::class         => [self::class, 'createVerboseFilter'],
            ContentTypeFilter::class     => [self::class, 'createContentTypeFilter'],
            CanDisplayFilter::class      => [self::class, 'createCanDisplayFilter'],
            HttpHandlerContract::class   => [self::class, 'createExceptionHandler'],
            Handler::class               => static function (ContainerInterface $container) {
                return $container->get(HttpHandlerContract::class);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions(): array
    {
        return [];
    }

    /**
     * Create a new Handler instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Contract\Exception\HttpHandler
     */
    public static function createExceptionHandler(ContainerInterface $container): HttpHandlerContract
    {
        $logger = null;

        if ($container->has(LoggerInterface::class)) {
            $logger = $container->get(LoggerInterface::class);
        }

        $config = $container->get('config');

        $handler = new Handler(
            $config,
            $container->get(ResponseFactoryInterface::class),
            $logger
        );

        $handler->addFilter($container->get(VerboseFilter::class), 32);
        $handler->addFilter($container->get(CanDisplayFilter::class), 64);
        $handler->addFilter($container->get(ContentTypeFilter::class), 128);

        $handler->addDisplayer($container->get(SymfonyDisplayer::class), 64);

        if ($container->has(FactoryContract::class)) {
            $handler->addDisplayer($container->get(ViewDisplayer::class), 128);
        }

        $handler->addDisplayer($container->get(HtmlDisplayer::class), 256);

        if (\class_exists(Run::class)) {
            $handler->addDisplayer($container->get(WhoopsPrettyDisplayer::class), 32);
            $handler->addDisplayer($container->get(WhoopsJsonDisplayer::class), 512);
        }

        $handler->addDisplayer($container->get(JsonDisplayer::class), 1024);
        $handler->addDisplayer($container->get(JsonApiDisplayer::class), 2048);

        return $handler;
    }

    /**
     * Create a new HtmlDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\HtmlDisplayer
     */
    public static function createHtmlDisplayer(ContainerInterface $container): HtmlDisplayer
    {
        return new HtmlDisplayer(
            $container->get(ResponseFactoryInterface::class),
            $container->get('config')
        );
    }

    /**
     * Create a new SymfonyDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\SymfonyDisplayer
     */
    public static function createSymfonyDisplayer(ContainerInterface $container): SymfonyDisplayer
    {
        return new SymfonyDisplayer($container->get(ResponseFactoryInterface::class));
    }

    /**
     * Create a new JsonDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\JsonDisplayer
     */
    public static function createJsonDisplayer(ContainerInterface $container): JsonDisplayer
    {
        return new JsonDisplayer($container->get(ResponseFactoryInterface::class));
    }

    /**
     * Create a new JsonApiDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\JsonApiDisplayer
     */
    public static function createJsonApiDisplayer(ContainerInterface $container): JsonApiDisplayer
    {
        return new JsonApiDisplayer($container->get(ResponseFactoryInterface::class));
    }

    /**
     * Create a new ViewDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\ViewDisplayer
     */
    public static function createViewDisplayer(ContainerInterface $container): ViewDisplayer
    {
        return new ViewDisplayer(
            $container->get(ResponseFactoryInterface::class),
            $container->get(FactoryContract::class)
        );
    }

    /**
     * Create a new WhoopsPrettyDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\WhoopsPrettyDisplayer
     */
    public static function createWhoopsPrettyDisplayer(ContainerInterface $container): WhoopsPrettyDisplayer
    {
        return new WhoopsPrettyDisplayer(
            $container->get(ResponseFactoryInterface::class),
            $container->get('config')
        );
    }

    /**
     * Create a new WhoopsJsonDisplayer instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Displayer\WhoopsJsonDisplayer
     */
    public static function createWhoopsJsonDisplayer(ContainerInterface $container): WhoopsJsonDisplayer
    {
        return new WhoopsJsonDisplayer($container->get(ResponseFactoryInterface::class));
    }

    /**
     * Create a new VerboseFilter instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \Viserio\Component\Exception\Filter\VerboseFilter
     */
    public static function createVerboseFilter(ContainerInterface $container): VerboseFilter
    {
        return new VerboseFilter($container->get('config'));
    }

    /**
     * Create a new ContentTypeFilter instance.
     *
     * @return \Viserio\Component\Exception\Filter\ContentTypeFilter
     */
    public static function createContentTypeFilter(): ContentTypeFilter
    {
        return new ContentTypeFilter();
    }

    /**
     * Create a new CanDisplayFilter instance.
     *
     * @return \Viserio\Component\Exception\Filter\CanDisplayFilter
     */
    public static function createCanDisplayFilter(): CanDisplayFilter
    {
        return new CanDisplayFilter();
    }
}
