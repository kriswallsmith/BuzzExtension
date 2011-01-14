<?php

namespace Buzz\Extension\Basecamp;

use Buzz\Browser as BaseBrowser;
use Buzz\Client\ClientInterface;
use Buzz\History\Journal;
use Buzz\Message\Request;
use Buzz\Message\Response;

/**
 * Wraps the Basecamp API.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @link   http://developer.37signals.com/basecamp/index.shtml
 */
class Browser extends BaseBrowser
{
    protected $host;
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param string $host   Your Basecamp host
     * @param string $apiKey Your Basecamp API key
     */
    public function __construct($host, $apiKey, ClientInterface $client = null, Journal $journal = null)
    {
        $this->host = $host;
        $this->apiKey = $apiKey;

        parent::__construct($client, $journal);
    }

    /** {@inheritDoc} */
    public function send(Request $request, Response $response = null)
    {
        $this->prepareRequest($request);

        return parent::send($request, $response);
    }

    protected function prepareRequest(Request $request)
    {
        if (!$request->getHost()) {
            $request->setHost($this->host);
        }

        $request->addHeader('Authorization: Basic '.base64_encode($this->apiKey.':X'));
        $request->addHeader('Content-Type: application/xml');
        $request->addHeader('Accept: application/xml');
    }
}
