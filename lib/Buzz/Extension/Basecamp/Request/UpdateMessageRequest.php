<?php

namespace Buzz\Extension\Basecamp\Request;

use Buzz\Extension\Basecamp\Resource\Message;

class UpdateMessageRequest extends AbstractMessageRequest
{
    protected $notifyAboutChanges = true;

    public function __construct($projectId, Message $message = null)
    {
        parent::__construct(static::METHOD_PUT, $projectId, $message);
    }

    public function setNotifyAboutChanges($notifyAboutChanges)
    {
        $this->notifyAboutChanges = $notifyAboutChanges;
    }

    protected function formatResource()
    {
        return sprintf('/projects/%d/posts/%d.xml', $this->projectId, $this->messageId);
    }

    protected function toXml()
    {
        $request = parent::toXml();
        $request->post->{'notify-about-changes'} = $this->notifyAboutChanges ? '1' : '0';
        return $request;
    }
}
