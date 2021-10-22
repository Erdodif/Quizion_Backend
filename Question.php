<?php
    class Question {
        private $id;
        private $quiz_id;
        private $content;
        private $no_right_answers;
        private $point;

        public function __construct(?int $id, int $quiz_id, string $content, int $no_right_answers, int $point)
        {
            $this->id = $id;
            $this->quiz_id = $quiz_id;
            $this->content = $content;
            $this->no_right_answers = $no_right_answers;
            $this->point = $point;
        }

        public static function Question($object) : Question{
            return new Question($object["id"], $object["quiz_id"], $object["content"], $object["no_right_answers"], $object["point"]);
        }

        public function getId() {
            return $this->id;
        }

        public function getQuizId() {
            return $this->quiz_id;
        }

        public function getContent() {
            return $this->content;
        }

        public function getNoRightAnswers() {
            return $this->no_right_answers;
        }

        public function getPoint() {
            return $this->point;
        }
    }
