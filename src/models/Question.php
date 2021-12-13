<?php

namespace Quizion\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

class Question extends Model
{
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["quiz_id"];


    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = Question::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("Question not found!")
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
        $result = Question::getById($id);
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
        $result = Question::getById($id);
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
            $result = Question::all();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no question!")
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

    static function getActives(): Data
    {
        try {
            $result = Question::where("active", "=", 1)->get();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no question!")
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
            $question = Question::create($input);
            $question->save();
            $data = new Data(
                RESPONSE_CREATED,
                $question
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

    static function getByQuiz($quiz_id): Data
    {
        if (Data::idIsValid($quiz_id)) {
            $actives = Question::where("quiz_id", "=", $quiz_id)->get();
            if (Quiz::getById($quiz_id)->getCode() == RESPONSE_OK) {
                if ($actives === null || empty($actives)) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("Empty result!")
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
                    new Message("Quiz #$quiz_id not found!")
                );
            }
        } else {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        return $data;
    }

    static function getByOrder($quiz_id,$question_order):Data
    {  
        $result = Question::getByQuiz($quiz_id);
        if ($result->getCode() == RESPONSE_OK) {
            if (Data::idIsValid($question_order)) {
                $data = $result->getDataRaw();
                if ($data === null || empty($data) || !isset($data[$question_order - 1])) {
                    $result->setCode(ERROR_NOT_FOUND);
                    $result->setData(new Message("Quiz #$quiz_id does not have $question_order. question!"));
                } else {
                    $result->setCode(RESPONSE_OK);
                    $result->setData($data[$question_order - 1]);
                }
            } else {
                $result->setCode(ERROR_BAD_REQUEST);
                $result->setData(new Message("Invalid question number reference!"));
            }
        }
        return $result;
    }
}
