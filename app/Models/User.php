<?php

namespace App\Models;

use App\Models\Table;
use App\Companion\Message;
use App\Companion\Data;
use Error;
use Exception;

class User extends Table{
    protected $table = "user";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["email","password"];
    
    static function getName():string{
        return "User";
    }
    static function getRequiredColumns(): array
    {
        return ["name", "email", "password"];
    }

    static function alterById($id, array|string $input):Data
    {
        $result = User::getById($id);
        if ($result->getCode() == RESPONSE_OK) {
            try {
                Data::castArray($input);
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
    
    static function addNew(array|string|null $input): Data
    {
        try {
            if ($input === null) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            } else {
                Data::castArray($input);
                $invalids = Data::inputErrors($input, User::getRequiredColumns());
                $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                if (!$invalids) {
                    $answer = User::create($input);
                    $answer->save();
                    $data = new Data(
                        RESPONSE_CREATED,
                        $answer
                    );
                } else {
                    $out = "";
                    foreach ($invalids as $invalid) {
                        $out .= $invalid . ", ";
                    }
                    $out = substr($out, 0, -2);
                    $data = new Data(
                        ERROR_BAD_REQUEST,
                        new Message("Missing " . $out)
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message($e)
            );
        } catch (Exception $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
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
