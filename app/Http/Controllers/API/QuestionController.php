<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result = Question::all();
            if (isset($result[0]["id"])) {
                return (new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                ))->toResponse();
            } else {
                return (new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no question!")
                ))->toResponse();
            }
        } catch (Error $e) {
            return (new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            ))->toResponse();
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ?int $quiz_id = null)
    {
        try {
            $request->validate([
                'quiz_id' => [isset($quiz) ? 'required' : 'nullable', 'numeric'],
                'content' => ['required', 'max:255', 'min:5'],
                'point' => ['required', 'numeric']
            ]);
            $question = new Question();
            $question->fill($request->only(['quiz_id', 'content', 'point']));
            if ($quiz_id !== null) {
                $question->quiz_id = $quiz_id;
            }
            $question->save();
            return (new Data(
                ResponseCodes::RESPONSE_CREATED,
                $question
            ))->toResponse();
        } catch (ValidationException $e) {
            $messagelist = [];
            foreach ($e->errors() as $key => $value) {
                array_push($messagelist, new Message($value[0], $key, MESSAGE_TYPE_STRING));
            }
            return (new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                Message::createBundle(...$messagelist)
            ))->toResponse();
        } catch (Exception $e) {
            return (new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message($e)
            ))->toResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $question)
    {
        return Question::getById($question)->toResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $question, Request $request)
    {
        try {
            $request->validate([
                'quiz_id' => ['nullable', 'numeric'],
                'content' => ['nullable', 'max:255', 'min:5'],
                'point' => ['nullable', 'numeric']
            ]);
            $result = Question::getById($question);
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result->toResponse();
            }
            try {
                $result->getDataRaw()->fill($request->only(['quiz_id','content','point']));
                $result->getDataRaw()->save();
                return $result->toResponse();
            } catch (Error $e) {
                return (new Data(
                    ResponseCodes::ERROR_INTERNAL,
                    new Message("An internal error occured: " . $e)
                ))->toResponse();
            }
        } catch (ValidationException $e) {
            $messagelist = [];
            foreach ($e->errors() as $key => $value) {
                array_push($messagelist, new Message($value[0], $key, MESSAGE_TYPE_STRING));
            }
            return (new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                Message::createBundle(...$messagelist)
            ))->toResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $question)
    {
        return Question::deleteById($question)->toResponse();
    }
}
