<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use \Exception;
use App\Companion\ResponseCodes;

class Question extends Table
{
    protected $table = "question";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["quiz_id"];
    static function getName(): string
    {
        return "Question";
    }
    static function getRequiredColumns(): array
    {
        return ["quiz_id", "content", "point"];
    }

    static function getActives(): Data
    {
        try {
            $result = Question::where("active", "=", 1)->get();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no question!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function getCountByQuiz($quiz_id): Data
    {
        if (Data::idIsValid($quiz_id)) {
            $count = Question::where("quiz_id", "=", $quiz_id)->count();
            if (Quiz::getById($quiz_id)->getCode() == ResponseCodes::RESPONSE_OK) {
                $data = new Data(
                    ResponseCodes::RESPONSE_OK,
                    new Message(
                        $count,
                        "count",
                        MESSAGE_TYPE_INT
                    )
                );
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("Quiz #$quiz_id not found!")
                );
            }
        } else {
            $data = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        return $data;
    }

    static function getAllByQuiz($quiz_id): Data
    {
        if (Data::idIsValid($quiz_id)) {
            $actives = Question::where("quiz_id", "=", $quiz_id)->get();
            if (Quiz::getById($quiz_id)->getCode() == ResponseCodes::RESPONSE_OK) {
                if ($actives === null || empty($actives)) {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Empty result!")
                    );
                } else {
                    $data = new Data(
                        ResponseCodes::RESPONSE_OK,
                        $actives
                    );
                }
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("Quiz #$quiz_id not found!")
                );
            }
        } else {
            $data = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        return $data;
    }

    static function getByOrder($quiz_id, $question_order): Data
    {
        $result = Question::getAllByQuiz($quiz_id);
        if ($result->getCode() == ResponseCodes::RESPONSE_OK) {
            if (Data::idIsValid($question_order)) {
                $data = $result->getDataRaw();
                if ($data === null || empty($data) || !isset($data[$question_order - 1])) {
                    $result->setCode(ResponseCodes::ERROR_NOT_FOUND);
                    $result->setData(new Message("Quiz #$quiz_id does not have $question_order. question!"));
                } else {
                    $result->setCode(ResponseCodes::RESPONSE_OK);
                    $result->setData($data[$question_order - 1]);
                }
            } else {
                $result->setCode(ResponseCodes::ERROR_BAD_REQUEST);
                $result->setData(new Message("Invalid question number reference!"));
            }
        }
        return $result;
    }
}
