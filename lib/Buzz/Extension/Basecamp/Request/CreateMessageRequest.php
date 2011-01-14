<?php

namespace Buzz\Extension\Basecamp\Request;

use Buzz\Extension\Basecamp\Resource\Message;

class CreateMessageRequest extends AbstractMessageRequest
{
    public function __construct($projectId, Message $message = null)
    {
        parent::__construct(static::METHOD_POST, $projectId, $message);
    }

    protected function formatResource()
    {
        return sprintf('/projects/%d/posts.xml', $this->projectId);
    }
}
