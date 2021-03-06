<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use App\Companion\ResponseCodes;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Hash;

class Token extends Model
{
    protected $table = "token";
    protected $guarded = ["id"];
    protected $hidden = ["id", "token", "created_at", "updated_at"];

    static function createKey(): string
    {
        return bin2hex(Str::random(64));
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
        if ($input === null ||( !isset($input["remember_token"]) && ( !isset($input["userID"]) || !isset($input["password"])))) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("Missing userID or password!")
            );
        } else {
            try {
                if (isset($input["remember_token"])){
                    $remember = $input["remember_token"];
                    $result = UserController::getByRemember($remember);
                    if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                        return new Data(
                            ResponseCodes::ERROR_BAD_REQUEST,
                            new Message("Invalid or expired remember token!")
                        );
                    }
                }
                else{
                    $userID = $input["userID"];
                    $password = $input["password"];
                    $result = UserController::getByAny($userID);
                    if ($result->getCode() !== ResponseCodes::RESPONSE_OK || !Hash::check($password, $result->getDataRaw()->password)) {
                        return new Data(
                            ResponseCodes::ERROR_BAD_REQUEST,
                            new Message("Invalid userID or password!")
                        );
                    }
                }
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
                $token->makeHidden(["user_id"]);
                $token->makeVisible(["token"]);
                if (isset($input["remember_token"])){
                    return new Data(
                        ResponseCodes::RESPONSE_CREATED,
                        $token
                    );
                }
                $remember = $result->getDataRaw()->remember_token;
                return new Data(
                    ResponseCodes::RESPONSE_CREATED,
                    Message::createBundle(
                        new Message($token->user()->name,"userName"),
                        new Message($token->token,"token"),
                        new Message($remember,"remember_token"))
                );
            } catch (Error $e) {
                return new Data(
                    ResponseCodes::ERROR_INTERNAL,
                    new Message($e->getMessage())
                );
            }
        }
    }

    function user(): User
    {
        return $this->belongsTo(User::class)->first();
    }
}
