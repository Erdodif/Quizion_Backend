<?php

namespace App\Companion;

class Message
{
    private string|array $content;
    private string|array $name;
    private string|array $type;
    public function __construct(?string $content = null,?string $name = null,string $type = MESSAGE_TYPE_STRING)
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
    static public function createBundle(...$messages){
        $out = new Message($messages[0]);
        for ($i=1; $i < count($messages); $i++) {
            $out->addMessage($messages[$i]);
        }
    }

    public function addMessage(Message $message){
        if(is_string($this->content)){
            $tempContent = $this->content;
            $this->content = [];
            array_push($this->content,$tempContent);
            $tempName = $this->name;
            $this->name = [];
            array_push($this->name,$tempName);
            $tempType = $this->type;
            $this->type = [];
            array_push($this->type,$tempType);
        }
        array_push($this->content,$message->content);
        array_push($this->name,$message->name);
        array_push($this->type,$message->type);
    }


    public function toJson(): string
    {//TODO javítás
        if(is_string($this->content)){
            $out = $this->content;
            if($this->type === MESSAGE_TYPE_STRING){
                $out = "\"$out\"";
            }
            return "{\"$this->name\":$out}";
        }
        $out = "{";
        for ($i=0; $i < count($this->co); $i++) {
            $out->addMessage($messages[$i]);
        }
        $row = $this->content;
        if($this->type === MESSAGE_TYPE_STRING){
            $row = "\"$row\"";
        }
        return "{\"$this->name\":$out}";
    }
    public function getContent(): string|array
    {
        return $this->content;
    }
}
define("MESSAGE_TYPE_STRING","string");
define("MESSAGE_TYPE_INT","int");
define("MESSAGE_TYPE_RAW","raw");
