<?php
require_once "Quiz.php";
require_once "Question.php";
require_once "Answer.php";
class Tables
{
    static public function getClassByName(?string $name, array $o = null): object
    {
        if (empty($name)) {
            throw new Exception("Nincs megadva táblanév!");
        }
        switch (mb_strtolower($name)) {
            case "quiz":
                return new Quiz($o);
            case "question":
                return new Question($o);
            case "answer":
                return new Answer($o);
                //case... 
            default:
                throw new Exception("Nem megfelelő táblanév!");
        }
    }
    static public function getClassKeysByName(string $name): array
    {
        return Tables::getClassByName($name)->getKeys();
    }

    public function getAll(): array
    {
        return get_object_vars($this);
    }

    public function getKeys(): array
    {
        $ki = [];
        foreach (get_object_vars($this) as $key => $value) {
            $ki[] = $key;
        }
        return $ki;
    }

    public function getNotNulls(): array
    {
        $ki = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value !== null) {
                $ki += array($key => $value);
            }
        }
        return $ki;
    }

    public function getNullKeys(): array
    {
        $ki = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value === null) {
                $ki[] = $key;
            }
        }
        return $ki;
    }
}
