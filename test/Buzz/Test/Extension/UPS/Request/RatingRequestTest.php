<?php

namespace Buzz\Test\Extension\UPS\Request;

use Buzz\Extension\UPS\Browser;
use Buzz\Extension\UPS\Request\RatingRequest;

class RatingRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSanityCheckConfigurators
     */
    public function testGetContent($configurator)
    {
        // sanity check
        $request = new RatingRequest();
        $configurator($request);
        $request->getContent();
    }

    public function provideSanityCheckConfigurators()
    {
        $address = $this->getMock('Buzz\\Extension\\UPS\\Resource\\AddressInterface');

        return array(
            array(function($request) { $request->setShipperNumber('123'); }),
            array(function($request) use($address) { $request->setShipperAddress($address); }),
            array(function($request) use($address) { $request->setShipToAddress($address); }),
            array(function($request) use($address) { $request->setShipFromAddress($address); }),
        );
    }

    public function testGetResource()
    {
        $request = new RatingRequest();
        $this->assertEquals('/ups.app/xml/Rate', $request->getResource(), '->getResource() defaults to the testing resource');
    }

    public function testGetHost()
    {
        $request = new RatingRequest();
        $this->assertEquals('https://wwwcie.ups.com', $request->getHost(), '->getHost() defaults to the testing host');
    }

    public function testSend()
    {
        $browser = $this->createBrowser();

        $address = $this->getMock('Buzz\\Extension\\UPS\\Resource\\AddressInterface');
        $address->expects($this->any())->method('getCity')->will($this->returnValue('New York City'));
        $address->expects($this->any())->method('getPostalCode')->will($this->returnValue('10011'));
        $address->expects($this->any())->method('getCountryCode')->will($this->returnValue('US'));
        $address->expects($this->any())->method('getStateProvinceCode')->will($this->returnValue('NY'));

        $request = new RatingRequest();
        $request->setShipperAddress($address);
        $request->setShipToAddress($address);
        $request->setPackageWeight(10);

        $response = $browser->send($request);
        $xml = new \SimpleXMLElement($response->getContent());

        $this->assertEquals(1, (integer) $xml->Response->ResponseStatusCode);
        $this->assertEquals('Success', (string) $xml->Response->ResponseStatusDescription);

        // // debug
        // $content = $request->getContent();
        // print_r(new \SimpleXMLElement(substr($content, strpos($content, '<RatingServiceSelectionRequest>'))));
        // print_r(new \SimpleXMLElement($response->getContent()));
    }

    protected function createBrowser()
    {
        if (!isset($_SERVER['UPS_ACCESS_LICENSE_NUMBER'])
            || !isset($_SERVER['UPS_USER_ID'])
            || !isset($_SERVER['UPS_PASSWORD'])) {
            $this->markTestSkipped('No UPS credentials configured');
        }

        return new Browser(
            $_SERVER['UPS_ACCESS_LICENSE_NUMBER'],
            $_SERVER['UPS_USER_ID'],
            $_SERVER['UPS_PASSWORD']
        );
    }
}
