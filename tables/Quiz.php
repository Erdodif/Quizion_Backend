<?php
class Quiz extends Table{
    private ?int $id;
    private string $header;
    private string $description;
    private bool $active;

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
