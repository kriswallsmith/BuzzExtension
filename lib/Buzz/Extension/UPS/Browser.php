<?php

namespace Buzz\Extension\UPS;

use Buzz\Browser as BaseBrowser;
use Buzz\Client\ClientInterface;
use Buzz\Extension\UPS\Listener\UpsListener;

class Browser extends BaseBrowser
{
    public function __construct($accessLicenseNumber, $userId, $password, ClientInterface $client = null)
    {
        parent::__construct($client);

        $this->addListener(new UpsListener($accessLicenseNumber, $userId, $password));
    }
}
