<?php
class Quiz{
    private $id;
    private $header;
    private $description;
    private $active;

    public function __construct($id, $header, $description, $active){
        $this->id = $id;
        $this->header = $header;
        $this->description = $description;
        $this->active = $active;
    }

    public static function Quiz($object) : Quiz{
        return new Quiz($object["id"], $object["header"], $object["description"], $object["active"]);
    }

    public function getId(){
        return $this->id;
    }
    public function getHeader(){
        return $this->header;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getActive(){
        return $this->active;
    }
}
