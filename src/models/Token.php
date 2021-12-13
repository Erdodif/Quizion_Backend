<?php

namespace Quizion\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

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
        try {
            return Token::where("key", $key)->firstOrFail();
        } catch (Error $e) {
            return false;
        }
    }

    static function getUserByToken(Token $token): User|false
    {
        try {
            return User::where("id", $token->user_id)->firtsOrFail();
        } catch (Error $e) {
            return false;
        }
    }

    static function addNewByLogin($input): Data
    {
        if ($input === null || !isset($input["userID"]) || !isset($input["password"])) {
            $result = new Data(
                ERROR_BAD_REQUEST,
                new Message("Missing userID or password!")
            );
        } else {
            try {
                $userID = $input["userID"];
                $password = $input["password"];
                $result = User::getByAny($userID);
                if ($result->getCode() == RESPONSE_OK && password_verify($password, $result->getDataRaw()->password)) {
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
                    $result->setCode(RESPONSE_CREATED);
                    $token->makeHidden(["user_id"]);
                    $token->makeVisible(["token"]);
                    $result->setData($token);
                } else {
                    $result->setCode(ERROR_BAD_REQUEST);
                    $result->setData(new Message("Invalid userID or password!"));
                }
            } catch (Error $e) {
                $result->setCode(ERROR_INTERNAL);
                $result->setData(new Message($e->getMessage()));
            }
        }
        return $result;
    }
}
