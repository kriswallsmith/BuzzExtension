<?php

namespace Buzz\Extension\Gdata\Contacts;

use Buzz\Browser as BaseBrowser;
use Buzz\Client\ClientInterface;
use Buzz\Extension\Gdata\Contacts\Resource\Contact;
use Buzz\Message\Factory\FactoryInterface;
use Buzz\Util\Url;

class Browser extends BaseBrowser
{
    private $accessToken;

    public function __construct($accessToken = null, ClientInterface $client = null, FactoryInterface $factory = null)
    {
        parent::__construct($client, $factory);

        $this->accessToken = $accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function all($email = 'default', $maxResults = 2000)
    {
        return $this->loadContacts(new Url('https://www.google.com/m8/feeds/contacts/'.$email.'/full?max-results='.$maxResults));
    }

    private function loadContacts(Url $url, array $contacts = array())
    {
        $request = $this->getMessageFactory()->createRequest();
        $url->applyToRequest($request);

        if ($this->accessToken) {
            $request->addHeader('Authorization: Bearer '.$this->accessToken);
        }

        $response = $this->send($request);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException($response->getReasonPhrase());
        }

        $feed = new \SimpleXMLElement($response->getContent());
        $feed->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
        $feed->registerXPathNamespace('gdata', 'http://schemas.google.com/g/2005');

        foreach ($feed->xpath('./atom:entry[atom:title and gdata:email[@address]]') as $entry) {
            $entry->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
            $entry->registerXPathNamespace('gdata', 'http://schemas.google.com/g/2005');

            $email = $entry->xpath('./gdata:email');

            $contacts[] = $contact = new Contact(
                (string) $entry->title,
                (string) $email[0]['address']
            );

            if ($this->accessToken && $photo = $entry->xpath('./atom:link[@rel="http://schemas.google.com/contacts/2008/rel#photo" and @href]')) {
                $contact->setPhoto((string) $photo[0]['href'].'?access_token='.rawurlencode($this->accessToken));
            }
        }

        if (!$next = $feed->xpath('./atom:link[@rel="next" and @href]')) {
            // all done
            return $contacts;
        }

        return $this->loadContacts(new Url((string) $next[0]['href']), $contacts);
    }
}
