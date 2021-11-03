<?php
class Answer extends Table{
    private ?int $id;
    private int $question_id;
    private string $content;
    private bool $is_right;

    public function __construct(?int $id,int $question_id,string $content,bool $is_right) {
        $this->id = $id;
        $this->id = $question_id;
        $this->id = $content;
        $this->id = $is_right;
    }

    public static function Answer($object) : Answer {
        return new Answer($object["id"], $object["question_id"],$object["content"],$object["is_right"]);
    }

    public function getId() {
        return $this->id;
    }

    public function getQuestionId() {
        return $this->question_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getIsRight() {
        return $this->is_right;
    }
}
