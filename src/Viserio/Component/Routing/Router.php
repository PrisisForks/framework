<?php
declare(strict_types=1);
namespace Viserio\Component\Routing;

use Closure;
use Interop\Container\ContainerInterface;
use Narrowspark\Arr\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Viserio\Component\Contracts\Routing\Route as RouteContract;
use Viserio\Component\Contracts\Routing\RouteCollection as RouteCollectionContract;
use Viserio\Component\Contracts\Routing\Router as RouterContract;
use Viserio\Component\Support\Traits\InvokerAwareTrait;
use Viserio\Component\Support\Traits\MacroableTrait;

class Router extends AbstractRouteDispatcher implements RouterContract
{
    use InvokerAwareTrait;
    use MacroableTrait;

    /**
     * The route group attribute stack.
     *
     * @var array
     */
    protected $groupStack = [];

    /**
     * The globally available parameter patterns.
     *
     * @var array
     */
    protected $patterns = [];

    /**
     * Create a new Router instance.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->routes    = new RouteCollection();
    }

    /**
     * Set the cache path for compiled routes.
     *
     * @param string $path
     *
     * @return \Viserio\Component\Contracts\Routing\Router
     */
    public function setCachePath(string $path): RouterContract
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the cache path for the compiled routes.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getCachePath(): string
    {
        return $this->path;
    }

    /**
     * Refresh cache file on development.
     *
     * @param bool $refreshCache
     *
     * @return \Viserio\Component\Contracts\Routing\Router
     */
    public function refreshCache(bool $refreshCache): RouterContract
    {
        $this->refreshCache = $refreshCache;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $uri, $action = null): RouteContract
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function patch(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function head(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('HEAD', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function options(string $uri, $action = null): RouteContract
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function any(string $uri, $action = null): RouteContract
    {
        return $this->addRoute(self::HTTP_METHOD_VARS, $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function match($methods, $uri, $action = null): RouteContract
    {
        return $this->addRoute(array_map('strtoupper', (array) $methods), $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function pattern(string $key, string $pattern): RouterContract
    {
        $this->patterns[$key] = $pattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function patterns(array $patterns): RouterContract
    {
        foreach ($patterns as $key => $pattern) {
            $this->pattern($key, $pattern);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(string $parameterName, string $expression): RouterContract
    {
        $this->globalParameterConditions[$parameterName] = $expression;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addParameters(array $parameterPatternMap): RouterContract
    {
        $this->globalParameterConditions += $parameterPatternMap;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function removeParameter(string $name)
    {
        unset($this->globalParameterConditions[$name]);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getParameters(): array
    {
        return $this->globalParameterConditions;
    }

    /**
     * {@inheritdoc}
     */
    public function group(array $attributes, $routes)
    {
        $this->updateGroupStack($attributes);

        $router = $this;

        if ($routes instanceof Closure) {
            $routes($router);
        } else {
            require $routes;
        }

        array_pop($this->groupStack);
    }

    /**
     * {@inheritdoc}
     */
    public function mergeWithLastGroup(array $new): array
    {
        return $this->mergeGroup($new, end($this->groupStack));
    }

    /**
     * {@inheritdoc}
     */
    public function mergeGroup(array $new, array $old): array
    {
        $new['namespace'] = $this->formatUsesPrefix($new, $old);
        $new['prefix']    = $this->formatGroupPrefix($new, $old);
        $new['suffix']    = $this->formatGroupSuffix($new, $old);

        if (isset($new['domain'])) {
            unset($old['domain']);
        }

        $new['where'] = array_merge(
            isset($old['where']) ? $old['where'] : [],
            isset($new['where']) ? $new['where'] : []
        );

        if (isset($old['as'])) {
            $new['as'] = $old['as'] . ($new['as'] ?? '');
        }

        return array_merge_recursive(Arr::except($old, ['namespace', 'prefix', 'suffix', 'where', 'as']), $new);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastGroupSuffix(): string
    {
        if (! empty($this->groupStack)) {
            $last = end($this->groupStack);

            return $last['suffix'] ?? '';
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getLastGroupPrefix(): string
    {
        if (! empty($this->groupStack)) {
            $last = end($this->groupStack);

            return isset($last['prefix']) ? $last['prefix'] : '';
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroupStack(): bool
    {
        return ! empty($this->groupStack);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getGroupStack(): array
    {
        return $this->groupStack;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getCurrentRoute()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getRoutes(): RouteCollectionContract
    {
        return $this->routes;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatchToRoute($request);
    }

    /**
     * Add a route to the underlying route collection.
     *
     * @param array|string               $methods
     * @param string                     $uri
     * @param \Closure|array|string|null $action
     *
     * @return \Viserio\Component\Contracts\Routing\Route
     */
    protected function addRoute($methods, string $uri, $action): RouteContract
    {
        return $this->routes->add($this->createRoute($methods, $uri, $action));
    }

    /**
     * Create a new route instance.
     *
     * @param array|string $methods
     * @param string       $uri
     * @param mixed        $action
     *
     * @return \Viserio\Component\Contracts\Routing\Route
     */
    protected function createRoute($methods, string $uri, $action): RouteContract
    {
        // If the route is routing to a controller we will parse the route action into
        // an acceptable array format before registering it and creating this route
        // instance itself. We need to build the Closure that will call this out.
        if ($this->actionReferencesController($action)) {
            $action = $this->convertToControllerAction($action);
        }

        $route = new Route($methods, $this->prefix($this->suffix($uri)), $action);
        $route->setContainer($this->getContainer());
        $route->setInvoker($this->getInvoker());

        if ($this->hasGroupStack()) {
            $this->mergeGroupAttributesIntoRoute($route);
        }

        $this->addWhereClausesToRoute($route);

        return $route;
    }

    /**
     * Add the necessary where clauses to the route based on its initial registration.
     *
     * @param \Viserio\Component\Contracts\Routing\Route $route
     */
    protected function addWhereClausesToRoute(RouteContract $route)
    {
        $where  = $route->getAction()['where'] ?? [];
        $patern = array_merge($this->patterns, $where);

        foreach ($patern as $name => $value) {
            $route->where($name, $value);
        }
    }

    /**
     * Merge the group stack with the controller action.
     *
     * @param \Viserio\Component\Contracts\Routing\Route $route
     */
    protected function mergeGroupAttributesIntoRoute(RouteContract $route)
    {
        $action = $this->mergeWithLastGroup($route->getAction());

        $route->setAction($action);
    }

    /**
     * Determine if the action is routing to a controller.
     *
     * @param string|array|\Closure $action
     *
     * @return bool
     */
    protected function actionReferencesController($action): bool
    {
        if ($action instanceof Closure) {
            return false;
        }

        return is_string($action) || (isset($action['uses']) && is_string($action['uses']));
    }

    /**
     * Add a controller based route action to the action array.
     *
     * @param array|string $action
     *
     * @return array
     */
    protected function convertToControllerAction($action): array
    {
        if (is_string($action)) {
            $action = ['uses' => $action];
        }

        if (! empty($this->groupStack)) {
            $action['uses'] = $this->prependGroupUses($action['uses']);
        }

        $action['controller'] = $action['uses'];

        return $action;
    }

    /**
     * Prepend the last group uses onto the use clause.
     *
     * @param string $uses
     *
     * @return string
     */
    protected function prependGroupUses(string $uses): string
    {
        $group = end($this->groupStack);

        return isset($group['namespace']) && mb_strpos($uses, '\\') !== 0 ? $group['namespace'] . '\\' . $uses : $uses;
    }

    /**
     * Prefix the given URI with the last prefix.
     *
     * @param string $uri
     *
     * @return string
     */
    protected function prefix(string $uri): string
    {
        $trimed = trim($this->getLastGroupPrefix(), '/') . '/' . trim($uri, '/');

        if (! $trimed) {
            return '/';
        } elseif (mb_substr($trimed, 0, 1) === '/') {
            return $trimed;
        }

        return '/' . $trimed;
    }

    /**
     * Suffix the given URI with the last suffix.
     *
     * @param string $uri
     *
     * @return string
     */
    protected function suffix(string $uri): string
    {
        return trim($uri) . trim($this->getLastGroupSuffix());
    }

    /**
     * Format the uses prefix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string|null
     */
    protected function formatUsesPrefix(array $new, array $old)
    {
        if (isset($new['namespace'])) {
            if (mb_strpos($new['namespace'], '\\') === 0) {
                return trim($new['namespace'], '\\');
            }

            return isset($old['namespace']) ?
                trim($old['namespace'], '\\') . '\\' . trim($new['namespace'], '\\') :
                trim($new['namespace'], '\\');
        }

        return $old['namespace'] ?? null;
    }

    /**
     * Format the prefix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string|null
     */
    protected function formatGroupPrefix(array $new, array $old)
    {
        $oldPrefix = $old['prefix'] ?? null;

        if (isset($new['prefix'])) {
            return trim($oldPrefix, '/') . '/' . trim($new['prefix'], '/');
        }

        return $oldPrefix;
    }

    /**
     * Format the suffix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string|null
     */
    protected function formatGroupSuffix(array $new, array $old)
    {
        $oldSuffix = $old['suffix'] ?? null;

        if (isset($new['suffix'])) {
            return trim($new['suffix']) . trim($oldSuffix);
        }

        return $oldSuffix;
    }

    /**
     * Update the group stack with the given attributes.
     *
     * @param array $attributes
     */
    protected function updateGroupStack(array $attributes)
    {
        if (! empty($this->groupStack)) {
            $attributes = $this->mergeGroup($attributes, end($this->groupStack));
        }

        $this->groupStack[] = $attributes;
    }
}