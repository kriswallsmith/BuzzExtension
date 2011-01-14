<?php

namespace Buzz\Test\Extension\Basecamp;

use Buzz\Extension\Basecamp\Browser;
use Buzz\Message\Request;

class BrowserTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestHeaders()
    {
        $host = 'foo.basecamphq.com';
        $apiKey = 'asdf';

        $client = $this->getMock('Buzz\\Client\\ClientInterface');
        $request = new Request();
        $response = $this->getMock('Buzz\\Message\\Response');

        $browser = new Browser($host, $apiKey, $client);
        $browser->send($request, $response);

        $this->assertEquals($host, $request->getHost());
        $this->assertRegExp('/^Basic/', $request->getHeader('Authorization'));
        $this->assertEquals('application/xml', $request->getHeader('Content-Type'));
        $this->assertEquals('application/xml', $request->getHeader('Accept'));
    }
}
