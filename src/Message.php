<?php

namespace Quizion\Backend;

class Message
{
    private $content;
    public function __construct($content)
    {
        $this->content = $content;
    }
    public function toJson(): String
    {
        return '{"message":"' . $this->content . '"}';
    }
    public function getContent(): String
    {
        return $this->content;
    }
}
