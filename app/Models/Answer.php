<?php

namespace App\Models;

use App\Models\Table;
use App\Models\Question;
use App\Companion\Message;
use App\Companion\Data;
use Illuminate\Database\Eloquent\Collection;

class Answer extends Table
{
    protected $table = "answer";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["question_id", "is_right"];

    static function getName(): string
    {
        return "Answer";
    }
    static function getRequiredColumns(): array
    {
        return ["question_id", "content", "is_right"];
    }

    static function getAllByQuestion($question_id): Data
    {
        if (Data::idIsValid($question_id)) {
            $actives = Answer::where("question_id", "=", $question_id)->get();
            if (Question::getById($question_id)->getCode() == RESPONSE_OK) {
                if ($actives === null || empty($actives)) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("Question #$question_id has no answers!")
                    );
                } else {
                    $data = new Data(
                        RESPONSE_OK,
                        $actives
                    );
                }
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("Question #$question_id not found!")
                );
            }
        } else {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message("Invalid question reference!")
            );
        }
        return $data;
    }

    static function getByQuestion($question_id, $answer_order): Data
    {
        $result = Answer::getAllByQuestion($question_id);
        if ($result->getCode() == RESPONSE_OK) {
            if (Data::idIsValid($answer_order)) {
                $data = $result->getDataRaw();
                if ($data === null || empty($data) || !isset($data[$answer_order - 1])) {
                    $result->setCode(ERROR_NOT_FOUND);
                    $result->setData(new Message("Question #$question_id does not have $answer_order. answer!"));
                } else {
                    $result->setCode(RESPONSE_OK);
                    $result->setData($data[$answer_order - 1]);
                }
            } else {
                $result->setCode(ERROR_BAD_REQUEST);
                $result->setData(new Message("Invalid answer number reference!"));
            }
        }
        return $result;
    }

    static function getAllByQuiz($quiz_id, $question_order): Data
    {
        $result = Question::getByOrder($quiz_id, $question_order);
        if ($result->getCode() == RESPONSE_OK) {
            $data = $result->getDataRaw();
            if (isset($data["id"])) {
                $question_id = $data["id"];
                $result->setCode(RESPONSE_OK);
                $result->setData(Answer::where("question_id", "=", $question_id)->get());
            } else {
                $result->setCode(ERROR_NOT_FOUND);
                $result->setData(new Message("The $question_order. question does not have answers!"));
            }
        }
        return $result;
    }

    static function getByQuiz($quiz_id, $question_order, $answer_order): Data
    {

        $result = Answer::getAllByQuiz($quiz_id, $question_order);
        if ($result->getCode() == RESPONSE_OK) {
            if (Data::idIsValid($answer_order)) {
                $data = $result->getDataRaw();
                if (isset($data[$answer_order - 1])) {
                    $result->setCode(RESPONSE_OK);
                    $result->setData($data[$answer_order - 1]);
                } else {
                    $result->setCode(ERROR_NOT_FOUND);
                    $result->setData(new Message("The $question_order. question does not have $answer_order. answer!"));
                }
            } else {
                $result->setCode(ERROR_BAD_REQUEST);
                $result->setData(new Message("Invalid answer order reference"));
            }
        }
        return $result;
    }

    static function getRightAnswersCount(Collection $answers): int
    {
        $count = 0;
        $answers->map(function ($element) use (&$count) {
            $count += $element->is_right;
        });
        return $count;
    }

    function seeRight()
    {
        $this->makeVisible(["is_right"]);
        $this->makeHidden(["content"]);
    }
}
