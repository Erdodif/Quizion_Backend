<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use App\Companion\ResponseCodes;

class Token extends Model
{
    protected $table = "token";
    protected $guarded = ["id"];
    protected $hidden = ["id", "token", "created_at", "updated_at"];

    static function createKey(): string
    {
        return bin2hex(random_bytes(64));
    }

    static function getTokenByKey(string $key): Token|false
    {
        $token = Token::where("token", $key)->first();
        if (!isset($token->id)) {
            $token = false;
        }
        return $token;
    }

    static function getUserByToken(Token $token): User|false
    {
        try {
            return User::where("id", $token->user_id)->firtsOrFail();
        } catch (Error $e) {
            return false;
        }
    }

    static function addNewByLogin(string|array $input): Data
    {
        Data::castArray($input);
        if ($input === null || !isset($input["userID"]) || !isset($input["password"])) {
            $result = new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Missing userID or password!")
            );
        } else {
            try {
                $userID = $input["userID"];
                $password = $input["password"];
                $result = User::getByAny($userID);
                if ($result->getCode() == ResponseCodes::RESPONSE_OK && password_verify($password, $result->getDataRaw()->password)) {
                    $stillNeeded = true;
                    while ($stillNeeded) {
                        try {
                            $key = Token::createKey();
                            $token = Token::create(array("user_id" => $result->getDataRaw()->id, "token" => $key));
                            $token->save();
                            $stillNeeded = false;
                        } catch (Error $e) {
                        }
                    }
                    $result->setCode(ResponseCodes::RESPONSE_CREATED);
                    $token->makeHidden(["user_id"]);
                    $token->makeVisible(["token"]);
                    $result->setData($token);
                } else {
                    $result->setCode(ResponseCodes::ERROR_BAD_REQUEST);
                    $result->setData(new Message("Invalid userID or password!"));
                }
            } catch (Error $e) {
                $result->setCode(ResponseCodes::ERROR_INTERNAL);
                $result->setData(new Message($e->getMessage()));
            }
        }
        return $result;
    }
}
