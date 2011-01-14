<?php

namespace Buzz\Extension\Basecamp\Request;

use Buzz\Extension\Basecamp\Resource\Message;
use Buzz\Message\Request;

/**
 * @todo support file attachments
 */
abstract class AbstractMessageRequest extends Request
{
    protected $projectId;
    protected $messageId;
    protected $categoryId;
    protected $title;
    protected $body;
    protected $private;
    protected $notify = array();

    public function __construct($method, $projectId, Message $message = null)
    {
        $this->projectId = $projectId;

        if ($message) {
            $this->fromMessage($message);
        }

        parent::__construct($method);
    }

    /**
     * Hydrates the current request with data from a message resource.
     *
     * @param Message $message A message
     */
    public function fromMessage(Message $message)
    {
        $this->setMessageId($message->id);
        $this->setCategoryId($message->categoryId);
        $this->setTitle($message->title);
        $this->setBody($message->body);
        $this->setPrivate($message->private);
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setPrivate($private)
    {
        $this->protected = $private;
    }

    public function addNotify($personId)
    {
        $this->notify[] = $personId;
    }

    /** {@inheritDoc} */
    public function getResource()
    {
        return $this->formatResource();
    }

    abstract protected function formatResource();

    /** {@inheritDoc} */
    public function getContent()
    {
        return $this->toXml()->asXml();
    }

    /**
     * Returns XML for the current request.
     *
     * @return SimpleXMLElement XML for the current request
     */
    protected function toXml()
    {
        $request = new \SimpleXMLElement('<request/>');
        $post = $request->addChild('post');

        if (null !== $this->categoryId) {
            $post->addChild('category-id', $this->categoryId);
        }

        if (null !== $this->title) {
            $post->addChild('title', $this->title);
        }

        if (null !== $this->body) {
            $post->addChild('body', $this->body);
        }

        if (null !== $this->private) {
            $post->addChild('private', $this->private ? '1' : '0');
        }

        foreach ($this->notify as $personId) {
            $request->addChild('notify', $personId);
        }

        return $request;
    }
}
