<?php

namespace Buzz\Extension\UPS\Request;

use Buzz\Browser;
use Buzz\BrowserAwareInterface;
use Buzz\Extension\UPS\Browser as UpsBrowser;
use Buzz\Extension\UPS\Resource\AddressInterface;
use Buzz\Message\Request;

class RatingRequest extends Request implements BrowserAwareInterface
{
    const URL_TEST = 'https://wwwcie.ups.com/ups.app/xml/Rate';
    const URL_PROD = 'https://onlinetools.ups.com/ups.app/xml/Rate';

    protected $accessLicenseNumber;
    protected $userId;
    protected $password;
    protected $shipperNumber;
    protected $shipperAddress;
    protected $shipToAddress;
    protected $shipFromAddress;
    protected $packageWeight;

    /**
     * Constructor.
     *
     * The URL defaults to the endpoint intended for integration testing.
     *
     * @param string $url The API endpoint
     */
    public function __construct($url = null)
    {
        $this->setMethod('POST');
        $this->fromUrl($url ?: static::URL_TEST);
    }

    public function setBrowser(Browser $browser)
    {
        if ($browser instanceof UpsBrowser) {
            $this->accessLicenseNumber = $browser->getAccessLicenseNumber();
            $this->userId = $browser->getUserId();
            $this->password = $browser->getPassword();
        }
    }

    public function setShipperNumber($shipperNumber)
    {
        $this->shipperNumber = $shipperNumber;
    }

    public function setShipperAddress(AddressInterface $address)
    {
        $this->shipperAddress = $address;
    }

    public function setShipToAddress(AddressInterface $address)
    {
        $this->shipToAddress = $address;
    }

    public function setShipFromAddress(AddressInterface $address)
    {
        $this->shipFromAddress = $address;
    }

    public function setPackageWeight($weight)
    {
        $this->packageWeight = $weight;
    }

    public function getContent()
    {
        return
            $this->buildAccessRequestXml()->asXml().
            $this->buildRatingServiceSelectionRequestXml()->asXml();
    }

    protected function buildAccessRequestXml()
    {
        $request = new \SimpleXmlElement('<AccessRequest xml:lang="en-US" />');
        $request->addChild('AccessLicenseNumber', $this->accessLicenseNumber);
        $request->addChild('UserId', $this->userId);
        $request->addChild('Password', $this->password);

        return $request;
    }

    protected function buildRatingServiceSelectionRequestXml()
    {
        $request = new \SimpleXmlElement(static::REQUEST_XML);

        // shipper
        if ($this->shipperNumber || $this->shipperAddress) {
            if ($this->shipperNumber) {
                $request->Shipment->Shipper->addChild('ShipperNumber', $this->shipperNumber);
            } else {
                unset($request->Shipment->RateInformation);
            }

            if ($this->shipperAddress) {
                static::buildAddress($this->shipperAddress, $request->Shipment->Shipper->Address);
            }
        } else {
            unset($request->Shipment->Shipper);
            unset($request->Shipment->RateInformation);
        }

        // ship to
        if ($this->shipToAddress) {
            static::buildAddress($this->shipToAddress, $request->Shipment->ShipTo->Address);
        } else {
            unset($request->Shipment->ShipTo);
        }

        // ship from
        if ($this->shipFromAddress) {
            static::buildAddress($this->shipFromAddress, $request->Shipment->ShipFrom->Address);
        } else {
            unset($request->Shipment->ShipFrom);
        }

        // package weight
        if ($this->packageWeight) {
            $request->Shipment->Package->PackageWeight->Weight = $this->packageWeight;
        } else {
            unset($request->Shipment->Package->PackageWeight->Weight);
        }

        return $request;
    }

    static protected function buildAddress(AddressInterface $address, \SimpleXmlElement $element)
    {
        $element->addChild('City', $address->getCity());
        $element->addChild('PostalCode', $address->getPostalCode());
        $element->addChild('CountryCode', $address->getCountryCode());
        $element->addChild('StateProvinceCode', $address->getStateProvinceCode());
    }

    const REQUEST_XML = <<<XML
<?xml version="1.0" ?>
<RatingServiceSelectionRequest>
    <Request>
        <TransactionReference>
            <CustomerContext>Rating and Service</CustomerContext>
            <XpciVersion>1.0</XpciVersion>
        </TransactionReference>
        <RequestAction>Rate</RequestAction>
        <RequestOption>Shop</RequestOption>
    </Request>
    <PickupType>
        <Code>01</Code>
        <Description>Regular Daily Pickup</Description>
    </PickupType>
    <Shipment>
        <Shipper>
            <Address/>
        </Shipper>
        <ShipTo>
            <Address>
                <ResidentialAddressIndicator/>
            </Address>
        </ShipTo>
        <ShipFrom>
            <Address/>
        </ShipFrom>
        <Service>
            <Code/>
        </Service>
        <Package>
            <PackagingType>
                <Code>00</Code>
            </PackagingType>
            <PackageWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight/>
            </PackageWeight>
        </Package>
        <RateInformation>
            <NegotiatedRatesIndicator/>
        </RateInformation>
    </Shipment>
</RatingServiceSelectionRequest>
XML;
}
