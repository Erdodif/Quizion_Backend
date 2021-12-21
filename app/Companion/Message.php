<?php

namespace App\Companion;

class Message
{
    private $content;
    private $name;
    private $type;
    public function __construct(string $content,?string $name = null,string $type = MESSAGE_TYPE_STRING)
    {
        $this->content = $content;
        if ($name === null){
            $this->name = "message";
        }
        else{
            $this->name = $name;
        }
        $this->type = $type;
    }
    public function toJson(): String
    {
        if($this->type === MESSAGE_TYPE_STRING){
            $out = "\"$this->content\"";
        }
        else{
            $out = $this->content;
        }
        return "{\"$this->name\":$out}";
    }
    public function getContent(): String
    {
        return $this->content;
    }
}
define("MESSAGE_TYPE_STRING","string");
define("MESSAGE_TYPE_INT","int");