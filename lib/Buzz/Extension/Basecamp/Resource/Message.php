<?php

namespace Buzz\Extension\Basecamp\Resource;

class Message
{
    public $id;
    public $categoryId;
    public $title;
    public $body;
    public $private;

    /**
     * Hydrates the current message with data from a <post/> element.
     *
     * @param SimpleXmlElement $post A <post/> element
     */
    public function fromXml(\SimpleXmlElement $post)
    {
        $this->id = (integer) $post->id;
        $this->categoryId = (integer) $post->{'category-id'};
        $this->title = (string) $post->title;
        $this->body = (string) $post->body;

        if (isset($post->private)) {
            $this->private = (Boolean) $post->private;
        }
    }
}
