<?php

namespace Quizion\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

class Quiz extends Model
{
    protected $table = "quiz";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["active"];

    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = Quiz::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("Quiz not found!")
                    );
                } else {
                    $data = new Data(
                        RESPONSE_OK,
                        $element
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function alterById($id, array $input)
    {
        $result = Quiz::getById($id);
        if ($result->getCode() == RESPONSE_OK) {
            try {
                $result->getDataRaw()->fill($input);
                $result->getDataRaw()->save();
            } catch (Error $e) {
                $result->setCode(ERROR_INTERNAL);
                $result->setData(new Message("An internal error occured: " . $e));
            }
        }
        return $result;
    }

    static function deleteById($id)
    {
        $result = Quiz::getById($id);
        try{
            if ($result->getCode() == RESPONSE_OK) {
                $result->getDataRaw()->delete();
                $result->setCode(RESPONSE_NO_CONTENT);
            }
        }
        catch (Error $e){
            $result->setCode(ERROR_INTERNAL);
            $result->setData(new Message("An internal error occured! ".$e));
        }
        return $result;
    }

    static function getAll()
    {
        try {
            $result = Quiz::all();
            $result->makeVisible(["active"]);
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no quiz!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function getActives(): Data
    {
        try {
            $result = Quiz::where("active", "=", 1)->get();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no quiz!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function addNew(array $input): Data
    {
        try {
            $quiz = Quiz::create($input);
            $quiz->save();
            $data = new Data(
                RESPONSE_CREATED,
                $quiz
            );
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
            );
        } finally {
            return $data;
        }
    }
}
