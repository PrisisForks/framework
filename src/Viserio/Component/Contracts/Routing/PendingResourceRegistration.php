<?php
declare(strict_types=1);
namespace Viserio\Component\Contracts\Routing;

interface PendingResourceRegistration extends MiddlewareAware
{
    /**
     * Set the methods the controller should apply to.
     *
     * @param string[] $methods
     *
     * @return $this
     */
    public function only(array $methods): self;

    /**
     * Set the methods the controller should exclude.
     *
     * @param string[] $methods
     *
     * @return $this
     */
    public function except(array $methods): self;

    /**
     * Set the route names for controller actions.
     *
     * @param string[] $names
     *
     * @return $this
     */
    public function addNames(array $names): self;

    /**
     * Set the route name for a controller action.
     *
     * @param string $method
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $method, string $name): self;

    /**
     * Override the route parameter names.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): self;

    /**
     * Override a route parameter's name.
     *
     * @param string $previous
     * @param string $new
     *
     * @return $this
     */
    public function setParameter(string $previous, string $new): self;
}