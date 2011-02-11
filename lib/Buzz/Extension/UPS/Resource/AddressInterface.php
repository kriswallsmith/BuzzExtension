<?php

namespace Buzz\Extension\UPS\Resource;

interface AddressInterface
{
    function getCity();
    function getPostalCode();
    function getCountryCode();
    function getStateProvinceCode();
}
