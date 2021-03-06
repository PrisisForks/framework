<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viserio\Component\Support\Tests;

use PHPUnit\Framework\TestCase;
use Viserio\Component\Support\Str;

/**
 * @internal
 *
 * @small
 */
final class PluralizerTest extends TestCase
{
    public function testBasicSingular(): void
    {
        self::assertEquals('child', Str::singular('children'));
    }

    public function testBasicPlural(): void
    {
        self::assertEquals('audio', Str::plural('audio', 1));
        self::assertEquals('children', Str::plural('child'));
    }

    public function testCaseSensitiveSingularUsage(): void
    {
        self::assertEquals('Child', Str::singular('Children'));
        self::assertEquals('CHILD', Str::singular('CHILDREN'));
        self::assertEquals('Test', Str::singular('Tests'));
    }

    public function testCaseSensitiveSingularPlural(): void
    {
        self::assertEquals('Children', Str::plural('Child'));
        self::assertEquals('CHILDREN', Str::plural('CHILD'));
        self::assertEquals('Tests', Str::plural('Test'));
    }

    public function testIfEndOfWordPlural(): void
    {
        self::assertEquals('VortexFields', Str::plural('VortexField'));
        self::assertEquals('MatrixFields', Str::plural('MatrixField'));
        self::assertEquals('IndexFields', Str::plural('IndexField'));
        self::assertEquals('VertexFields', Str::plural('VertexField'));
    }
}
