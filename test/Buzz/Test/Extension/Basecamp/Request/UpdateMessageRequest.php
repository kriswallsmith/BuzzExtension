<?php

namespace Buzz\Test\Extension\Basecamp\Request;

use Buzz\Extension\Basecamp\Request\UpdateMessageRequest;

class UpdateMessageRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testMethod()
    {
        $request = new UpdateMessageRequest(123);
        $this->assertEquals(UpdateMessageRequest::METHOD_PUT, $request->getMethod());
    }

    public function testResource()
    {
        $request = new UpdateMessageRequest(123);
        $request->setMessageId(456);
        $this->assertEquals('/projects/123/posts/456.xml', $request->getResource());
    }

    public function testContent()
    {
        $request = new UpdateMessageRequest(123);
        $request->setMessageId(456);
        $request->setCategoryId(789);
        $request->setTitle('title');
        $request->setBody('body');

        $expected = <<<XML
<?xml version="1.0"?>
<request><post><id>456</id><category-id>789</category-id><title>title</title><body>body</body></post></request>

XML;

        $this->assertEquals($expected, $request->getContent());
    }
}
