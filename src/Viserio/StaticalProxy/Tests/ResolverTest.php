<?php
declare(strict_types=1);
namespace Viserio\StaticalProxy\Tests;

use Viserio\StaticalProxy\Resolver;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveWithoutRegex()
    {
        $resolver = new Resolver('pattern', 'stdClass');

        $this->assertSame('stdClass', $resolver->resolve('pattern'));
    }

    public function testResolveWithRegex()
    {
        $resolver = new Resolver('Pattern\*', '$1');

        $this->assertSame('stdClass', $resolver->resolve('Pattern\stdClass'));
    }

    public function testFailingResolve()
    {
        $resolver = new Resolver('pattern', 'translation');

        $this->assertFalse($resolver->resolve('other_pattern'));
        $this->assertFalse($resolver->resolve('pattern'));
    }

    public function testMatches()
    {
        $resolver = new Resolver('pattern', 'translation');

        $this->assertTrue($resolver->matches('pattern'));
        $this->assertTrue($resolver->matches('pattern', 'translation'));
        $this->assertFalse($resolver->matches('other_pattern', 'translation'));
        $this->assertFalse($resolver->matches('pattern', 'other_translation'));
        $this->assertFalse($resolver->matches('other_pattern', 'other_translation'));
        $this->assertFalse($resolver->matches('other_pattern'));
    }
}
