<?php
declare(strict_types=1);
namespace Viserio\Cookie\Tests;

use Cake\Chronos\Chronos;
use DateTime;
use Mockery as Mock;
use Narrowspark\TestingHelper\Traits\MockeryTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Viserio\Cookie\Cookie;
use Viserio\Cookie\SetCookie;
use Viserio\Cookie\RequestCookies;
use Viserio\HttpFactory\ServerRequestFactory;

class RequestCookiesTest extends \PHPUnit_Framework_TestCase
{
    use MockeryTrait;

    public function tearDown()
    {
        parent::tearDown();

        $this->allowMockingNonExistentMethods(true);

        // Verify Mockery expectations.
        Mock::close();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The object [Viserio\Cookie\SetCookie] must be an instance of \Viserio\Cookie\Cookie
     */
    public function testRequestCookiesToThrowException()
    {
        new RequestCookies([new SetCookie('test', 'test')]);
    }

    public function testAddCookieToHeaderAndBack()
    {
        $cookie = new Cookie('encrypted', 'jiafs89320jadfa');
        $cookie2 = new Cookie('encrypted2', 'jiafs89320jadfa');
        $request = (new ServerRequestFactory())->createServerRequest($_SERVER);
        $cookies = RequestCookies::fromRequest($request);
        $cookies = $cookies->add($cookie);
        $cookies = $cookies->add($cookie2);
        $request = $cookies->renderIntoCookieHeader($request);
        $cookies = RequestCookies::fromRequest($request);

        self::assertSame($cookie->getName(), $cookies->get('encrypted')->getName());
        self::assertSame($cookie->getValue(), $cookies->get('encrypted')->getValue());
    }

    /**
     * @dataProvider provideParsesFromCookieStringWithoutExpireData
     *
     * Cant test with automatic expires, test are one sec to slow.
     */
    public function testFromCookieHeaderWithoutExpire($cookieString, array $expectedCookies)
    {
        $request = $this->mock(Request::class);
        $request->shouldReceive('getHeaderLine')->with('Cookie')->andReturn($cookieString);

        $cookies = RequestCookies::fromRequest($request);

        foreach ($cookies->getAll() as $name => $cookie) {
            self::assertEquals($expectedCookies[$name]->getName(), $cookie->getName());
            self::assertEquals($expectedCookies[$name]->getValue(), $cookie->getValue());
        }
    }

    /**
     * @dataProvider provideGetsCookieByNameData
     */
    public function testItGetsCookieByName(string $cookieString, string $cookieName, Cookie $expectedCookie)
    {
        $request = $this->mock(Request::class);
        $request->shouldReceive('getHeaderLine')->with('Cookie')->andReturn($cookieString);

        $cookies = RequestCookies::fromRequest($request);

        self::assertEquals($expectedCookie->getName(), $cookies->get($cookieName)->getName());
        self::assertEquals($expectedCookie->getValue(), $cookies->get($cookieName)->getValue());
    }

    /**
     * @dataProvider provideParsesFromCookieStringWithoutExpireData
     */
    public function testItKnowsWhichCookiesAreAvailable(string $setCookieStrings, array $expectedSetCookies)
    {
        $request = $this->mock(Request::class);
        $request->shouldReceive('getHeaderLine')->with('Cookie')->andReturn($setCookieStrings);

        $setCookies = RequestCookies::fromRequest($request);

        foreach ($expectedSetCookies as $expectedSetCookie) {
            self::assertTrue($setCookies->has($expectedSetCookie->getName()));
        }

        self::assertFalse($setCookies->has('i know this cookie does not exist'));
    }

    public function provideParsesFromCookieStringWithoutExpireData()
    {
        return [
            [
                'some;',
                [new Cookie('some')],
            ],
            [
                'someCookie=',
                [new Cookie('someCookie')],
            ],
            [
                'someCookie=someValue',
                [new Cookie('someCookie', 'someValue')],
            ],
            [
                'someCookie=someValue; someCookie3=someValue3',
                [
                    new Cookie('someCookie', 'someValue'),
                    new Cookie('someCookie3', 'someValue3'),
                ],
            ],
        ];
    }

    public function provideGetsCookieByNameData()
    {
        return [
            ['someCookie=someValue', 'someCookie', new Cookie('someCookie', 'someValue')],
            ['someCookie=', 'someCookie', new Cookie('someCookie')],
            ['hello=world; someCookie=someValue; token=abc123', 'someCookie', new Cookie('someCookie', 'someValue')],
            ['hello=world; someCookie=; token=abc123', 'someCookie', new Cookie('someCookie')],
        ];
    }
}
