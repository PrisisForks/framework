<?php
declare(strict_types=1);
namespace Viserio\Routing\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Viserio\Routing\Tests\Fixture\FooMiddleware;
use Viserio\Routing\Traits\MiddlewareAwareTrait;

class MiddlewareAwareTraitTest extends TestCase
{
    use MiddlewareAwareTrait;

    public function testWithAndWithoutMiddleware()
    {
        $this->withMiddleware(FooMiddleware::class);

        self::assertSame([FooMiddleware::class], $this->middlewares);

        $this->withoutMiddleware(FooMiddleware::class);

        self::assertSame([], $this->middlewares);
    }
}
