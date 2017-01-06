<?php
declare(strict_types=1);
namespace Viserio\View\Tests\Middleware;

use Mockery as Mock;
use Narrowspark\TestingHelper\Middleware\DelegateMiddleware;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Viserio\Contracts\Session\Store as StoreContract;
use Viserio\Contracts\View\Factory as FactoryContract;
use Viserio\HttpFactory\ResponseFactory;
use Viserio\HttpFactory\ServerRequestFactory;
use Viserio\View\Middleware\ShareErrorsFromSessionMiddleware;

class ShareErrorsFromSessionMiddlewareTest extends TestCase
{
    use MockeryTrait;

    public function tearDown()
    {
        parent::tearDown();

        $this->allowMockingNonExistentMethods(true);

        // Verify Mockery expectations.
        Mock::close();
    }

    public function testProcess()
    {
        $session = $this->mock(StoreContract::class);
        $session->shouldReceive('get')
            ->once()
            ->with('errors', [])
            ->andReturn([]);

        $view = $this->mock(FactoryContract::class);
        $view->shouldReceive('share')
            ->once()
            ->with('errors', []);

        $middleware = new ShareErrorsFromSessionMiddleware($view);

        $request = (new ServerRequestFactory())->createServerRequest($_SERVER);
        $request = $request->withAttribute('session', $session);

        $response = $middleware->process($request, new DelegateMiddleware(function ($request) {
            return (new ResponseFactory())->createResponse(200);
        }));

        static::assertInstanceOf(ResponseInterface::class, $response);
    }
}
