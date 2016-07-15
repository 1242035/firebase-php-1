<?php

namespace Kreait\Tests\Firebase\Http;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Kreait\Firebase\Http\Middleware;
use Psr\Http\Message\RequestInterface;

class MiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $given
     * @param $expected
     *
     * @dataProvider uriStringProvider
     */
    public function testEnsureJson($given, $expected)
    {
        $request = new Request('GET', $given);

        $h = new MockHandler([
            function (RequestInterface $request) use ($expected) {
                $this->assertEquals($expected, (string) $request->getUri());
                $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
            }
        ]);

        $stack = new HandlerStack($h);
        $stack->push(Middleware::ensureJson());

        $computedStack = $stack->resolve();
        $computedStack($request);
    }

    public function uriStringProvider()
    {
        return [
            'root_with_extension' => [
                'http://domain.tld/.json',
                'http://domain.tld/.json',
            ],
            'root_without_extension' => [
                'http://domain.tld',
                'http://domain.tld/.json',
            ],
            'resource_with_extension' => [
                'http://domain.tld/resource.json',
                'http://domain.tld/resource.json',
            ],
            'resource_without_extension' => [
                'http://domain.tld/resource',
                'http://domain.tld/resource.json',
            ],
        ];
    }
}
