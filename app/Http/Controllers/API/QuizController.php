<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result = Quiz::where("active", "=", 1)->get();
            if (isset($result[0]["id"])) {
                return (new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                ))->toResponse();
            } else {
                return (new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no quiz!")
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            $result = Quiz::all();
            if (isset($result[0]["id"])) {
                return (new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                ))->toResponse();
            } else {
                return (new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no quiz!")
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
    public function store(Request $request)
    {
        try {
            $request->validate([
                'header' => ['required', 'max:255', 'min:5'],
                'description' => ['required', 'max:255', 'min:8'],
                'active' => ['nullable', 'boolean'],
                'seconds_per_quiz' => ['nullable', 'numeric', 'max:120']
            ]);
            $quiz = Quiz::create($request->only(['header', 'description', 'active', 'seconds_per_quiz']));
            $quiz->save();
            return (new Data(
                ResponseCodes::RESPONSE_CREATED,
                $quiz
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
    public function show($id)
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = Quiz::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message("Quiz not found!")
                    );
                } else {
                    $data = new Data(
                        ResponseCodes::RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data->toResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'header' => ['nullable','max:255', 'min:5'],
                'description' => ['nullable', 'max:255', 'min:8'],
                'active' => ['nullable', 'boolean'],
                'seconds_per_quiz' => ['nullable', 'numeric', 'max:120']
            ]);
            $result = Quiz::getById($id);
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result->toResponse();
            }
            try {
                $result->getDataRaw()->fill($request->only(['header','description','active','seconds_per_quiz']));
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
    public function destroy($id)
    {
        $result = Quiz::getById($id);
        try {
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result;
            }
            $result->getDataRaw()->delete();
            return (new Data(
                ResponseCodes::RESPONSE_NO_CONTENT,
                null
            ))->toResponse();
        } catch (Error $e) {
            return (new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e)
            ))->toResponse();
        }
    }
}
