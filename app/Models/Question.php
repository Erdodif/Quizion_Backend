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
        if (!Data::idIsValid($quiz_id)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        $count = Question::where("quiz_id", "=", $quiz_id)->count();
        if (Quiz::getById($quiz_id)->getCode() !== ResponseCodes::RESPONSE_OK) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz #$quiz_id not found!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            new Message(
                $count,
                "count",
                MESSAGE_TYPE_INT
            )
        );
    }

    static function getAllByQuiz($quiz_id): Data
    {
        if (!Data::idIsValid($quiz_id)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        $quiz = Quiz::find($quiz_id);
        if (!isset($quiz["id"])) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz #$quiz_id not found!")
            );
        }
        $questions = $quiz->questions();
        if ($questions === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz #$quiz_id does not have questions!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $questions
        );
    }

    static function getByOrder($quiz_id, $question_order): Data
    {
        if (!Data::idIsValid($quiz_id)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid quiz reference!")
            );
        }
        $quiz = Quiz::find($quiz_id);
        if (!isset($quiz["id"])) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz #$quiz_id not found!")
            );
        }
        if (!Data::idIsValid($question_order)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid question order reference!")
            );
        }
        $question = $quiz->question($question_order);
        if ($question === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz #$quiz_id does not have $question_order. question!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $question
        );
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
            return null;
        }
        return $collection[$order - 1];
    }

    function quiz(): Quiz
    {
        return $this->belongsTo(Quiz::class)->first();
    }
}
