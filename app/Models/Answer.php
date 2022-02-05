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

    static function getAllByQuestion($question_id): Data
    {
        if (!Data::idIsValid($question_id)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid question reference!")
            );
        }
        $question = Question::find($question_id);
        if ($question === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Question #$question_id not found!")
            );
        }
        $answers = $question->answers();
        if ($answers === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Question #$question_id has no answers!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $answers
        );
    }

    static function getByQuestion($question_id, $answer_order): Data
    {
        if (!Data::idIsValid($question_id)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid question reference!")
            );
        }
        $question = Question::find($question_id);
        if ($question === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Question #$question_id not found!")
            );
        }
        if (!Data::idIsValid($answer_order)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid answer order reference!")
            );
        }
        $answer = $question->answer($answer_order);
        if ($answer === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Question #$question_id does not have $answer_order. answer!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $answer
        );
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
