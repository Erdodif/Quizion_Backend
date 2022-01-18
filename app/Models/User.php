<?php

namespace App\Models;

use App\Models\Table;
use App\Companion\Message;
use App\Companion\Data;
use Error;
use Exception;
use App\Companion\ResponseCodes;
use Illuminate\Database\Eloquent\Collection;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Foundation\Auth\User as Authenticatable;

//JAVÍTANI
class User extends Authenticatable //Table
{
    protected $table = "users";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["email", "password", "remember_token"];

    static function getName(): string
    {
        return "Users";
    }
    static function getRequiredColumns(): array
    {
        return ["name", "email", "password"];
    }

    static function alterById($id, array|string $input): Data
    {
        $result = User::getById($id);
        if ($result->getCode() == ResponseCodes::RESPONSE_OK) {
            try {
                Data::castArray($input);
                if (isset($input["password"])) {
                    $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                }
                $result->getDataRaw()->fill($input);
                $result->getDataRaw()->save();
            } catch (Error $e) {
                $result->setCode(ResponseCodes::ERROR_INTERNAL);
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
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            } else {
                Data::castArray($input);
                $invalids = Data::inputErrors($input, User::getRequiredColumns());
                $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                if (!$invalids) {
                    $stillNeeded = true;
                    $problemhere = 0;
                    $PROBLEM = "";
                    while ($stillNeeded && $problemhere< 100) {
                        try{
                            $input["remember_token"] = Token::createKey();
                            $user = User::create($input);
                            $user->save();
                            $stillNeeded = false;
                        }
                        catch(Exception $e){
                            $problemhere++;
                            $PROBLEM = $e;
                        }
                    }
                    if($stillNeeded){
                        throw $PROBLEM;
                    }
                    $data = new Data(
                        ResponseCodes::RESPONSE_CREATED,
                        $user);
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
            return $data;
        }
    }

    static function getByName($identifier): Data
    {
        if (!isset($identifier)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid userID or password!")
            );
        }
        $user = User::where("name", "=", $identifier)->get();
        if (!isset($user[0])) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("User not found")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $user[0]
        );
    }

    static function getByEmail($identifier): Data
    {
        if (!isset($identifier)) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Invalid userID or password!")
            );
        }
        $user = User::where("email", "=", $identifier)->get();
        if (!isset($user[0])) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("User not found")
            );
        }
        return new Data(
            ResponseCodes::RESPONSE_OK,
            $user[0]
        );
    }

    static function getByAny($identifier): Data
    {
        if (is_numeric($identifier)) {
            return User::getById($identifier);
        } if (str_contains($identifier, "@")) {
            return User::getByEmail($identifier);
        }
        return User::getByName($identifier);
    }

    function results(): Collection|null
    {
        $collection = $this->hasMany(Result::class)->get();
        return Data::collectionOrNull($collection);
    }

    function tokens(){
        $collection = $this->hasMany(Token::class)->get();
        return Data::collectionOrNull($collection);
    }

//------------------------------IDEIGLENES------------------------------
    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                $data = new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::find($id);
                if (!isset($element["id"])) {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message(static::getName() . " not found!")
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
            return $data;
        }
    }

    static function getByIds($ids): Data
    {
        try {
            $idsAreValid = false;
            try {
                $i = 0;
                while ($i < count($ids) && Data::idIsValid($ids[$i])) {
                    $i++;
                }
                $idsAreValid = $i >= count($ids);
            } catch (Error $e) {
                $idsAreValid = false;
            }
            if (!$idsAreValid) {
                $data = new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            } else {
                $element = self::whereIn("id", $ids)->get();
                if (!isset($element[0]["id"])) {
                    $data = new Data(
                        ResponseCodes::ERROR_NOT_FOUND,
                        new Message(static::getName() . " not found!")
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
            return $data;
        }
    }

    static function getAll(): Data
    {
        try {
            $result = self::all();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no " . strtolower(static::getName()) . "!")
                );
            }
        } catch (Error $e) {
            $data = new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        } finally {
            return $data;
        }
    }

    static function deleteById($id): Data
    {
        $result = self::getById($id);
        try {
            if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                return $result;
            }
            $result->getDataRaw()->delete();
            return new Data(
                ResponseCodes::RESPONSE_NO_CONTENT,
                null
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e)
            );
        }
    }
//------------------------------IDEIGLENES------------------------------
}
