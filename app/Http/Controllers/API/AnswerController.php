<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Answer::getAll()->toResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,?int $question_id = null)
    {
        try {
            $request->validate([
                'question_id' => isset($question_id)?['required','numeric']:['nullable','numeric'],
                'content' => ['required','max:255','min:1'],
                'is_right' => ['required','boolean']
            ]);
            $answer = Answer::create($request->only(['question_id','content','is_right']));
            if (isset($question_id)){
                $answer->question_id = $question_id;
            }
            $answer->save();
            return (new Data(
                ResponseCodes::RESPONSE_CREATED,
                $answer
            ))->toResponse();
        } catch (ValidationException $e) {
            $messagelist = [];
            foreach($e->errors() as $key => $value){
                array_push($messagelist, new Message($value[0],$key,MESSAGE_TYPE_STRING));
            }
            return (new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                Message::createBundle(...$messagelist)
            ))->toResponse();
        }
        catch (Exception $e) {
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
    public function show(int $answer)
    {
        return Answer::getById($answer)->toResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,int $answer)
    {
        return Answer::alterById($answer, $request->toArray())->toResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $answer)
    {
        return Answer::deleteById($answer)->toResponse();
    }
}
