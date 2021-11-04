<?php
class Quiz extends Tables
{
    protected ?int $id;
    protected ?string $header;
    protected ?string $description;
    protected ?bool $active;

    public function __construct(?array $id = null, ?string $header = null, ?string $description = null, ?bool $active = null)
    {
        if (!(is_int($id) || $id === null)) {
            $header = $id["header"] ?? null;
            $description = $id["description"] ?? null;
            $active = $id["active"] ?? null;
            $id = $id["id"] ?? null;
        }
        $this->id = $id;
        $this->header = $header;
        $this->description = $description;
        $this->active = $active;
    }

    public static function Quiz($object): Quiz
    {
        return new Quiz($object["id"], $object["header"], $object["description"], $object["active"]);
    }

    public function getId()
    {
        return $this->id;
    }
    public function getHeader()
    {
        return $this->header;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getActive()
    {
        return $this->active;
    }
}
