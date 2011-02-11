<?php

namespace Buzz\Extension\UPS;

use Buzz\Browser as BaseBrowser;
use Buzz\Client\ClientInterface;
use Buzz\History\Journal;

class Browser extends BaseBrowser
{
    protected $accessLicenseNumber;
    protected $userId;
    protected $password;

    public function __construct($accessLicenseNumber, $userId, $password, ClientInterface $client = null, Journal $journal = null)
    {
        $this->accessLicenseNumber = $accessLicenseNumber;
        $this->userId = $userId;
        $this->password = $password;

        parent::__construct($client, $journal);
    }

    public function getAccessLicenseNumber()
    {
        return $this->accessLicenseNumber;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
