<?php

namespace Buzz\Extension\UPS\Listener;

use Buzz\Extension\UPS\Request\UpsRequestInterface;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

class UpsListener implements ListenerInterface
{
    private $accessLicenseNumber;
    private $userId;
    private $password;

    public function __construct($accessLicenseNumber, $userId, $password)
    {
        $this->accessLicenseNumber = $accessLicenseNumber;
        $this->userId = $userId;
        $this->password = $password;
    }

    public function preSend(RequestInterface $request)
    {
        if ($request instanceof UpsRequestInterface) {
            $request->setAccessLicenseNumber($this->accessLicenseNumber);
            $request->setUserId($this->userId);
            $request->setPassword($this->password);
        }
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
