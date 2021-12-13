<?php

namespace Quizion\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Models\Question;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

class Answer extends Model
{
    protected $table = "answer";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["question_id", "is_right"];

    static public function getName()
    {
        return "answer";
    }


    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = Answer::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("Answer not found!")
                    );
                } else {
                    $data = new Data(
                        RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function alterById($id, array $input)
    {
        $result = Answer::getById($id);
        if ($result->getCode() == RESPONSE_OK) {
            try {
                $result->getDataRaw()->fill($input);
                $result->getDataRaw()->save();
            } catch (Error $e) {
                $result->setCode(ERROR_INTERNAL);
                $result->setData(new Message("An internal error occured: " . $e));
            }
        }
        return $result;
    }

    static function deleteById($id)
    {
        $result = Answer::getById($id);
        try {
            if ($result->getCode() == RESPONSE_OK) {
                $result->getDataRaw()->delete();
                $result->setCode(RESPONSE_NO_CONTENT);
            }
        } catch (Error $e) {
            $result->setCode(ERROR_INTERNAL);
            $result->setData(new Message("An internal error occured! " . $e));
        }
        return $result;
    }

    static function getAll()
    {
        try {
            $result = Answer::all();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no answer!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function addNew(array $input): Data
    {
        try {
            $answer = Answer::create($input);
            $answer->save();
            $data = new Data(
                RESPONSE_CREATED,
                $answer
            );
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
            );
        } finally {
            return $data;
        }
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

    static function getByQuiz($quiz_id, $question_order, $answer_order):Data
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

    function seeRight(){
        $this->makeVisible(["is_right"]);
        $this->makeHidden(["content"]);
    }
}
