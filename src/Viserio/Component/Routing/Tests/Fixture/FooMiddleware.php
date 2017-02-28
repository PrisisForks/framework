<?php
declare(strict_types=1);
namespace Viserio\Component\Routing\Tests\Fixture;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class FooMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        $request = $request->withAttribute('foo-middleware', 'foo-middleware');

        $response = $delegate->process($request);

        return $response;
    }
}