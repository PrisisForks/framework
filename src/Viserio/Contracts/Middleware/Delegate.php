<?php
declare(strict_types=1);
namespace Viserio\Contracts\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Delegate
{
    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function next(RequestInterface $request): ResponseInterface;
}