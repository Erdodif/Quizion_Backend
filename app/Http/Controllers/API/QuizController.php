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
                $data = new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no quiz!")
                );
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->toArray();
        try {
            if ($input === null) {
                $data = new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            } else {
                Data::castArray($input);
                $invalids = Data::inputErrors($input, Quiz::getRequiredColumns());
                if (!$invalids) {
                    $answer = Quiz::create($input);
                    $answer->save();
                    $data = new Data(
                        ResponseCodes::RESPONSE_CREATED,
                        $answer
                    );
                } else {
                    $out = "";
                    foreach ($invalids as $invalid) {
                        $out .= $invalid . ", ";
                    }
                    $out = substr($out, 0, -2);
                    $data = new Data(
                        ResponseCodes::ERROR_BAD_REQUEST,
                        new Message("Missing " . $out)
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message($e)
            );
        } catch (Exception $e) {
            $data = new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message($e)
            );
        } finally {
            return $data->toResponse();
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
        $input = $request->toArray();
        try {
            Data::castArray($input);
            $result = $this->show($id);
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result;
            }
            try {
                $result->getDataRaw()->fill($input);
                $result->getDataRaw()->save();
                return $result->toResponse();
            } catch (Error $e) {
                return (new Data(
                    ResponseCodes::ERROR_INTERNAL,
                    new Message("An internal error occured: " . $e)
                ))->toResponse();
            }
        } catch (Error $e) {
            return (new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("The given Data is missing or invalid!")
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
        $result = $this->show($id);
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
