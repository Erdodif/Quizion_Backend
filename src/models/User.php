<?php

namespace Quizion\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

class User extends Model{
    protected $table = "user";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["email","password"];
    
    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = User::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ERROR_NOT_FOUND,
                        new Message("User not found!")
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

    static function alterById($id, array $input):Data
    {
        $result = User::getById($id);
        if ($result->getCode() == RESPONSE_OK) {
            try {
                if (isset($input["password"])){
                    $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                }
                $result->getDataRaw()->fill($input);
                $result->getDataRaw()->save();
            } catch (Error $e) {
                $result->setCode(ERROR_INTERNAL);
                $result->setData(new Message("An internal error occured: " . $e));
            }
        }
        return $result;
    }

    static function deleteById($id):Data
    {
        $result = User::getById($id);
        try {
            if ($result->getCode() == RESPONSE_OK) {
                $result->getDataRaw()->delete();
                $result->setCode(RESPONSE_NO_CONTENT);
            }
        } catch (Error $e) {
            $result->setCode(ERROR_INTERNAL);
            $result->setData(new Message("An internal error occured! " . $e));
        }
        return $result;
    }
    
    static function addNew(array $input): Data
    {
        try {
            if (isset($input["password"])){
                $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
            }
            $user = User::create($input);
            $user->save();
            $data = new Data(
                RESPONSE_CREATED,
                $user
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

    static function getAll():Data
    {
        try {
            $result = User::all();
            $result->makeVisible(["active"]);
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no user!")
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

    static function getByName($identifier): Data
    {
        if (isset($identifier)) {
            $user = User::where("name", "=", $identifier)->get();
            if (!isset($user[0])) {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("User not found")
                );
            } else {
                $data = new Data(
                    RESPONSE_OK,
                    $user[0]
                );
            }
        } else {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message("Invalid userID or password!")
            );
        }
        return $data;
    }

    static function getByEmail($identifier):Data
    {
        if (isset($identifier)) {
            $user = User::where("email", "=", $identifier)->get();
            if (!isset($user[0])) {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("User not found")
                );
            } else {
                $data = new Data(
                    RESPONSE_OK,
                    $user[0]
                );
            }
        } else {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message("Invalid userID or password!")
            );
        }
        return $data;
    }
    
    static function getByAny($identifier): Data
    {
        if (is_numeric($identifier)) {
            $result = User::getById($identifier);
        } else if (str_contains($identifier, "@")) {
            $result = User::getByEmail($identifier);
        } else {
            $result = User::getByName($identifier);
        }
        return $result;
    }
}
