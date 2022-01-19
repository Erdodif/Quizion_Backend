<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\API\QuizQuestionController;
use App\Http\Controllers\API\AnswerController;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;

class QuizAnswerController extends Controller
{

    static function getAllByQuiz($quiz_id, $question_order): Data
    {
        $result = QuizQuestionController::getByOrder($quiz_id, $question_order);
        if ($result->getCode() != ResponseCodes::RESPONSE_OK) {
            return $result;
        }
        $answers = $result->getDataRaw()->answers();
        if ($answers == null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("The $question_order. question does not have answers!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $answers
        );
    }

    static function getByQuiz($quiz_id, $question_order, $answer_order): Data
    {
        $result = QuizQuestionController::getByOrder($quiz_id, $question_order);
        if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
            return $result;
        }
        if (!Data::idIsValid($answer_order)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid answer order reference")
            );
        }
        $answer = $result->getDataRaw()->answer($answer_order);
        if ($answer === null) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("The $question_order. question does not have $answer_order. answer!")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $answer
        );
    }

    static function getIdByQuiz($quiz_id, $question_order, $answer_order): int
    {
        return static::getByQuiz($quiz_id, $question_order, $answer_order)->getDataRaw()->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($quiz_id, $question_order)
    {
        return static::getAllByQuiz($quiz_id, $question_order)->toResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($quiz_id, $question_order, Request $request)
    {
        $input = $request->toArray();
        $input["question_id"] = QuizQuestionController::getIdByOrder($quiz_id, $question_order);
        $request = array_merge($request->all(),["question_id"=> QuizQuestionController::getIdByOrder($quiz_id, $question_order)]);
        /*return (new Data(
            ResponseCodes::ERROR_IM_A_TEAPOT,
            Message::createBundle(
                new Message($input["content"],"content"),
                new Message($input["is_right"],"is_right",MESSAGE_TYPE_INT),
                new Message($input["question_id"],"question_id",MESSAGE_TYPE_INT),
            )
        ))->toResponse();*/
        return redirect()->action(
            [AnswerController::class,'store'],
            ['request' => $request]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($quiz_id, $question_order, $answer_id)
    {
        return static::getByQuiz($quiz_id, $question_order, $answer_id)->toResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($quiz_id, $question_order, $answer_order, Request $request)
    {
        $id = static::getIdByQuiz($quiz_id, $question_order, $answer_order);
        return redirect()->action(
            [AnswerController::class,'update'],
            ['answer' => $id, 'request' => $request]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($quiz_id, $question_order, $answer_order)
    {
        $id = static::getIdByQuiz($quiz_id, $question_order, $answer_order);
        return redirect()->action(
            [AnswerController::class,'destroy'],
            ['answer' => $id]
        );
    }
}
