<?php
class Question extends Tables
{
    protected ?int $id;
    protected ?int $quiz_id;
    protected ?string $content;
    protected ?int $no_right_answers;
    protected ?int $point;

    public function __construct(?object $id = null, ?int $quiz_id = null, ?string $content = null, ?int $no_right_answers = null, ?int $point = null)
    {
        if(!(is_int($id) || $id ===null)){
            $quiz_id = $id["quiz_id"];
            $content = $id["content"];
            $no_right_answers = $id["no_right_answers"];
            $point = $id["point"];
            $id = $id["id"];
        }
        $this->id = $id;
        $this->quiz_id = $quiz_id;
        $this->content = $content;
        $this->no_right_answers = $no_right_answers;
        $this->point = $point;
    }

    public static function Question($object): Question
    {
        return new Question($object["id"], $object["quiz_id"], $object["content"], $object["no_right_answers"], $object["point"]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuizId()
    {
        return $this->quiz_id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getNoRightAnswers()
    {
        return $this->no_right_answers;
    }

    public function getPoint()
    {
        return $this->point;
    }
}
