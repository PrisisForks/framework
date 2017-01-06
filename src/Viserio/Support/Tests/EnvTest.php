<?php
declare(strict_types=1);
namespace Viserio\Support\Tests;

use PHPUnit\Framework\TestCase;
use Viserio\Support\Env;

class EnvTest extends TestCase
{
    public function setUp()
    {
        putenv('TEST_TRUE=true');
        putenv('TEST_FALSE=false');
        putenv('TEST_FALSE_2=(false)');
        putenv('TEST_NULL=null');
        putenv('TEST_NUM=25');
        putenv('TEST_EMPTY=empty');
        putenv('TEST_NORMAL=teststring');
        putenv('TEST_QUOTES="teststring"');
        putenv('TEST_BASE64=base64:dGVzdA==');
    }

    public function tearDown()
    {
        putenv('TEST_TRUE=');
        putenv('TEST_FALSE=');
        putenv('TEST_FALSE_2=');
        putenv('TEST_NULL=');
        putenv('TEST_NUM=');
        putenv('TEST_EMPTY=');
        putenv('TEST_NORMAL=');
        putenv('TEST_QUOTES=');
        putenv('TEST_BASE64=');
        putenv('TEST_TRUE');
        putenv('TEST_FALSE');
        putenv('TEST_FALSE_2');
        putenv('TEST_NULL');
        putenv('TEST_NUM');
        putenv('TEST_EMPTY');
        putenv('TEST_NORMAL');
        putenv('TEST_QUOTES');
        putenv('TEST_BASE64');
    }

    public function testGet()
    {
        self::assertTrue(Env::get('TEST_TRUE'));
        self::assertFalse(Env::get('NOT_SET', false));
        self::assertSame('test', Env::get('NOT_SET2', function () {
            return 'test';
        }));
        self::assertFalse(Env::get('TEST_FALSE'));
        self::assertFalse(Env::get('TEST_FALSE_2'));
        self::assertSame(null, Env::get('TEST_NULL'));
        self::assertSame(25, Env::get('TEST_NUM'));
        self::assertSame('', Env::get('TEST_EMPTY'));
        self::assertSame('teststring', Env::get('TEST_NORMAL'));
        self::assertSame('teststring', Env::get('TEST_QUOTES'));
        self::assertSame('test', Env::get('TEST_BASE64'));
    }
}
