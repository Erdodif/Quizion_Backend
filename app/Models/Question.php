<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use \Exception;
use App\Companion\ResponseCodes;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\ErrorHandler\Collecting;

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
            $quiz = Quiz::find($quiz_id);
            if (isset($quiz["id"])) {
                $questions = $quiz->questions();
                if ($questions !== null) {
                    $data = new Data(
                        ResponseCodes::RESPONSE_OK,
                        $questions
                    );
                } else {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Empty result!")
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
        if (Data::idIsValid($quiz_id)) {
            $quiz = Quiz::find($quiz_id);
            if (Data::idIsValid($question_order)) {
                if (isset($quiz["id"])) {
                    $question = $quiz->question($question_order);
                    if ($question !== null) {
                        $data = new Data(
                            ResponseCodes::RESPONSE_OK,
                            $question
                        );
                    } else {
                        $data = new Data(
                            ResponseCodes::ERROR_NOT_FOUND,
                            new Message("Quiz #$quiz_id does not have $question_order. question!")
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
                    new Message("Invalid question order reference!")
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

    function answers(): Collection|null
    {
        $collection = $this->hasMany(Answer::class)->get();
        return Data::collectionOrNull($collection);
    }

    function answer(int $order): Answer|null
    {
        $collection = $this->hasMany(Answer::class)->get();
        if ($collection->count() < $order) {
            $out = null;
        } else {
            $out = $collection[$order - 1];
        }
        return $out;
    }

    function quiz(): Quiz
    {
        return $this->belongsTo(Quiz::class)->first();
    }
}
