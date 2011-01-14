<?php

namespace Buzz\Test\Extension\Basecamp\Request;

use Buzz\Extension\Basecamp\Request\CreateMessageRequest;

class CreateMessageRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testMethod()
    {
        $request = new CreateMessageRequest(123);
        $this->assertEquals(CreateMessageRequest::METHOD_POST, $request->getMethod());
    }

    public function testResource()
    {
        $request = new CreateMessageRequest(123);
        $this->assertEquals('/projects/123/posts.xml', $request->getResource());
    }

    public function testContent()
    {
        $request = new CreateMessageRequest(123);
        $request->setCategoryId(456);
        $request->setTitle('title');
        $request->setBody('body');

        $expected = <<<XML
<?xml version="1.0"?>
<request><post><category-id>456</category-id><title>title</title><body>body</body></post></request>

XML;

        $this->assertEquals($expected, $request->getContent());
    }
}
