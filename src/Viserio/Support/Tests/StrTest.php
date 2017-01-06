<?php
declare(strict_types=1);
namespace Viserio\Support\Tests;

use PHPUnit\Framework\TestCase;
use Viserio\Support\Str;

class StrTest extends TestCase
{
    public function testStringCanBeLimitedByWords()
    {
        self::assertEquals('Narrowspark...', Str::words('Narrowspark Viserio', 1));
        self::assertEquals('Narrowspark___', Str::words('Narrowspark Viserio', 1, '___'));
        self::assertEquals('Narrowspark Viserio', Str::words('Narrowspark Viserio', 3));
    }

    public function testStringWithoutWordsDoesntProduceError()
    {
        $nbsp = chr(0xC2) . chr(0xA0);
        self::assertEquals(' ', Str::words(' '));
        self::assertEquals($nbsp, Str::words($nbsp));
    }

    public function testStringTrimmedOnlyWhereNecessary()
    {
        self::assertEquals(' Narrowspark Viserio ', Str::words(' Narrowspark Viserio ', 3));
        self::assertEquals(' Narrowspark...', Str::words(' Narrowspark Viserio ', 1));
    }

    public function testParseCallback()
    {
        self::assertEquals(['Class', 'method'], Str::parseCallback('Class@method', 'foo'));
        self::assertEquals(['Class', 'foo'], Str::parseCallback('Class', 'foo'));
    }

    public function testStrFinish()
    {
        self::assertEquals('test/string/', Str::finish('test/string', '/'));
        self::assertEquals('test/string/', Str::finish('test/string/', '/'));
        self::assertEquals('test/string/', Str::finish('test/string//', '/'));
    }

    public function testStrLimit()
    {
        $string = 'Narrowspark Framework for Creative People.';

        self::assertEquals('Narrows...', Str::limit($string, 7));
        self::assertEquals('Narrows', Str::limit($string, 7, ''));
        self::assertEquals('Narrowspark Framework for Creative People.', Str::limit($string, 100));
        self::assertEquals('Narrowspark...', Str::limit('Narrowspark Framework for Creative People.', 11));
        self::assertEquals('这是一...', Str::limit('这是一段中文', 6));
    }

    public function testRandom()
    {
        self::assertEquals(64, mb_strlen(Str::random()));
        $randomInteger = mt_rand(1, 100);
        self::assertEquals($randomInteger, mb_strlen(Str::random($randomInteger)));
        self::assertInternalType('string', Str::random());

        $result = Str::random(20);
        self::assertTrue(is_string($result));
        self::assertEquals(20, mb_strlen($result));
    }

    public function testSubstr()
    {
        self::assertEquals('Ё', Str::substr('БГДЖИЛЁ', -1));
        self::assertEquals('ЛЁ', Str::substr('БГДЖИЛЁ', -2));
        self::assertEquals('И', Str::substr('БГДЖИЛЁ', -3, 1));
        self::assertEquals('ДЖИЛ', Str::substr('БГДЖИЛЁ', 2, -1));
        self::assertEmpty(Str::substr('БГДЖИЛЁ', 4, -4));
        self::assertEquals('ИЛ', Str::substr('БГДЖИЛЁ', -3, -1));
        self::assertEquals('ГДЖИЛЁ', Str::substr('БГДЖИЛЁ', 1));
        self::assertEquals('ГДЖ', Str::substr('БГДЖИЛЁ', 1, 3));
        self::assertEquals('БГДЖ', Str::substr('БГДЖИЛЁ', 0, 4));
        self::assertEquals('Ё', Str::substr('БГДЖИЛЁ', -1, 1));
        self::assertEmpty(Str::substr('Б', 2));
    }

    public function testSnakeCase()
    {
        self::assertEquals('narrowspark_p_h_p_framework', Str::snake('NarrowsparkPHPFramework'));
        self::assertEquals('narrowspark_php_framework', Str::snake('NarrowsparkPhpFramework'));

        // snake cased strings should not contain spaces
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark Php Framework'));
        self::assertEquals('narrowspark_php_framework', Str::snake('narrowspark php framework'));
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark  Php  Framework'));

        // test cache
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark  Php  Framework'));

        // `Str::snake()` should not duplicate the delimeters
        self::assertEquals('narrowspark_php_framework', Str::snake('narrowspark_php_framework'));
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark_Php_Framework'));
        self::assertEquals('narrowspark-php-framework', Str::snake('Narrowspark_Php_Framework', '-'));
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark_ _Php_ _Framework'));
        self::assertEquals('narrowspark_php_framework', Str::snake('Narrowspark     Php    Framework'));
        self::assertEquals('narrowspaaaark_phppp_framewoooork!!!', Str::snake('Narrowspaaaark Phppp Framewoooork!!!'));
        self::assertEquals('narrowspark_php_framework', Str::snake('NarrowsparkPhp_Framework'));

        self::assertEquals('foo_bar', Str::snake('Foo Bar'));
        self::assertEquals('foo_bar', Str::snake('foo bar'));
        self::assertEquals('foo_bar', Str::snake('FooBar'));
        self::assertEquals('foo_bar', Str::snake('fooBar'));
        self::assertEquals('foo_bar', Str::snake('foo-bar'));
        self::assertEquals('foo_bar', Str::snake('foo_bar'));
        self::assertEquals('foo_bar', Str::snake('FOO_BAR'));
        self::assertEquals('foo_bar', Str::snake('fooBar'));
        self::assertEquals('foo_bar', Str::snake('fooBar')); // test cache
    }

    public function testKebabCase()
    {
        self::assertEquals('foo-bar', Str::snake('Foo Bar', '-'));
        self::assertEquals('foo-bar', Str::snake('foo bar', '-'));
        self::assertEquals('foo-bar', Str::snake('FooBar', '-'));
        self::assertEquals('foo-bar', Str::snake('fooBar', '-'));
        self::assertEquals('foo-bar', Str::snake('foo-bar', '-'));
        self::assertEquals('foo-bar', Str::snake('foo_bar', '-'));
        self::assertEquals('foo-bar', Str::snake('FOO_BAR', '-'));
        self::assertEquals('foo-bar', Str::snake('fooBar', '-'));
        self::assertEquals('foo-bar', Str::snake('fooBar', '-')); // test cache
    }

    public function testStudlyCase()
    {
        //StudlyCase <=> PascalCase
        self::assertEquals('FooBar', Str::studly('Foo Bar'));
        self::assertEquals('FooBar', Str::studly('foo bar'));
        self::assertEquals('FooBar', Str::studly('FooBar'));
        self::assertEquals('FooBar', Str::studly('fooBar'));
        self::assertEquals('FooBar', Str::studly('foo-bar'));
        self::assertEquals('FooBar', Str::studly('foo_bar'));
        self::assertEquals('FooBar', Str::studly('FOO_BAR'));
        self::assertEquals('FooBar', Str::studly('foo_bar'));
        self::assertEquals('FooBar', Str::studly('foo_bar')); // test cache
        self::assertEquals('FooBarBaz', Str::studly('foo-barBaz'));
        self::assertEquals('FooBarBaz', Str::studly('foo-bar_baz'));
    }
}
