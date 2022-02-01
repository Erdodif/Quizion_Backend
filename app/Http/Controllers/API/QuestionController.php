<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Question;
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
        return Question::all()->toJson();
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
                'quiz_id' => [isset($quiz_id) ? 'required' : 'nullable', 'numeric'],
                'content' => ['required', 'max:255', 'min:5'],
                'point' => ['required', 'numeric']
            ]);
            $answer = Question::create($request->only(['quiz_id', 'content', 'point']));
            if (isset($question_id)) {
                $answer->question_id = $question_id;
            }
            $answer->save();
            return (new Data(
                ResponseCodes::RESPONSE_CREATED,
                $answer
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
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message($e)
            );
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
        return Question::alterById($question, $request->only(['content', 'point']))->toResponse();
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
