<?php

namespace Buzz\Extension\UPS\Resource;

class Address implements AddressInterface
{
    private $city;
    private $stateProvinceCode;
    private $postalCode;
    private $countryCode;

    public function __construct($city = null, $stateProvinceCode = null, $postalCode = null, $countryCode = null)
    {
        $this->city = $city;
        $this->stateProvinceCode = $stateProvinceCode;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getStateProvinceCode()
    {
        return $this->stateProvinceCode;
    }

    public function setStateProvinceCode($stateProvinceCode)
    {
        $this->stateProvinceCode = $stateProvinceCode;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }
}
