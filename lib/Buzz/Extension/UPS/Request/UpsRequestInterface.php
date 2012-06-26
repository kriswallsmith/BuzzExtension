<?php

namespace Buzz\Extension\UPS\Request;

interface UpsRequestInterface
{
    function setAccessLicenseNumber($accessLicenseNumber);
    function setUserId($userId);
    function setPassword($password);
}
