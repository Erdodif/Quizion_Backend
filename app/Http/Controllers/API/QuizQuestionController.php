<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
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

    static function getIdByOrder($quiz_id, $question_order): int
    {
        return static::getByOrder($quiz_id, $question_order)->getDataRaw()->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $quiz_id)
    {
        return static::getAllByQuiz($quiz_id)->toResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $quiz_id, Request $request)
    {
        return redirect()->action(
            [QuestionController::class, 'store'],
            [
                'request' => $request->only(["content", "point"]),
                'quiz_id' => $quiz_id
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function count(int $quiz_id)
    {
        return static::getCountByQuiz($quiz_id)->toResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $quiz_id, int $question_order)
    {
        return static::getByOrder($quiz_id, $question_order)->toResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $quiz_id, int $question_order, Request $request)
    {
        $id = static::getIdByOrder($quiz_id, $question_order);
        return redirect()->action(
            [QuestionController::class, 'update'],
            [
                'question' => $id,
                'request' => $request->only(["content", "point"])
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $quiz_id, int $question_order)
    {
        $id = static::getIdByOrder($quiz_id, $question_order);
        return redirect()->action(
            [QuestionController::class, 'destroy'],
            [
                'question' => $id
            ]
        );
    }
}
