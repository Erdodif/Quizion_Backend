<?php

namespace App\Models;

use App\Models\Table;
use App\Models\Question;
use App\Companion\Message;
use App\Companion\Data;
use Illuminate\Database\Eloquent\Collection;
use App\Companion\ResponseCodes;

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
            $question = Question::find($question_id);
            if ($question !== null) {
                $answers = $question->answers();
                if ($answers !== null) {
                    $data = new Data(
                        ResponseCodes::RESPONSE_OK,
                        $answers
                    );
                } else {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Question #$question_id has no answers!")
                    );
                }
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("Question #$question_id not found!")
                );
            }
        } else {
            $data = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid question reference!")
            );
        }
        return $data;
    }

    static function getByQuestion($question_id, $answer_order): Data
    {
        if (Data::idIsValid($question_id)) {
            $question = Question::find($question_id);
            if (Data::idIsValid($answer_order)) {
                if (isset($question["id"])) {
                    $answer = $question->answer($answer_order);
                    if ($answer !== null) {
                        $data = new Data(
                            ResponseCodes::RESPONSE_OK,
                            $answer
                        );
                    } else {
                        $data = new Data(
                            ResponseCodes::ERROR_NOT_FOUND,
                            new Message("Question #$question_id does not have $answer_order. answer!")
                        );
                    }
                } else {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Question #$question_id not found!")
                    );
                }
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid answer order reference!")
                );
            }
        } else {
            $data = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid question reference!")
            );
        }
        return $data;
    }

    static function getAllByQuiz($quiz_id, $question_order): Data
    {
        $result = Question::getByOrder($quiz_id, $question_order);
        if ($result->getCode() == ResponseCodes::RESPONSE_OK) {
            $question = $result->getDataRaw();
            $answers = $question->answers();
            if ($answers !== null) {
                $result->setCode(ResponseCodes::RESPONSE_OK);
                $result->setData($answers);
            } else {
                $result->setCode(ResponseCodes::ERROR_NOT_FOUND);
                $result->setData(new Message("The $question_order. question does not have answers!"));
            }
        }
        return $result;
    }

    static function getByQuiz($quiz_id, $question_order, $answer_order): Data
    {
        $result = Question::getByOrder($quiz_id, $question_order);
        if ($result->getCode() == ResponseCodes::RESPONSE_OK) {
            $question = $result->getDataRaw();
            if (Data::idIsValid($answer_order)) {
                $answer = $question->answer($answer_order);
                if ($answer !== null) {
                    $result->setCode(ResponseCodes::RESPONSE_OK);
                    $result->setData($answer);
                } else {
                    $result->setCode(ResponseCodes::ERROR_NOT_FOUND);
                    $result->setData(new Message("The $question_order. question does not have $answer_order. answer!"));
                }
            } else {
                $result->setCode(ResponseCodes::ERROR_BAD_REQUEST);
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

    function question(): Question
    {
        return $this->belongsTo(Question::class)->first();;
    }

    function quiz(): Quiz
    {
        return $this->question()->belongsTo(Quiz::class)->first();
    }
}
