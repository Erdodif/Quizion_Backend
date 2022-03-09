<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Token;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    static function addNew(UserRequest $request) : Data
    {
        try {
            if ($request === null) {
                return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            }
            $request["password"] = Hash::make($request["password"]);
            $request["remember_token"] = Token::createKey();
            $stillNeeded = true;
            $problemhere = 0;
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            $user->save();
            while ($stillNeeded && $problemhere < 100) {
                try {
                    $user->remember_token = Token::createKey();
                    $user->save();
                    $stillNeeded = false;
                } catch (Exception $e) {
                    $problemhere++;
                }
            }
            if ($problemhere == 100) {
                throw new Error("Unsuccessful operation!");
            }
            return new Data(
                ResponseCodes::RESPONSE_CREATED,
                $user
            );
        } catch (Error | Exception $e) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message($e)
            );
        }
    }

    static function getById($id): Data
    {
        try {
            if (!Data::idIsValid($id)) {
                return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Invalid id reference!")
                );
            }
            $element = User::find($id);
            if (!isset($element["id"])) {
                return new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("User not found!")
                );
            }
            return new Data(
                ResponseCodes::RESPONSE_OK,
                $element
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        }
    }

    static function getByRemember($token): Data
    {
        if (!isset($token)) {
            return new Data(
                ResponseCodes::ERROR_UNAUTHORIZED,
                new Message("Invalid or expired token!")
            );
        }
        $user = User::where("remember_token", "=", $token)->get();
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
            return UserController::getById($identifier);
        }
        if (str_contains($identifier, "@")) {
            return UserController::getByEmail($identifier);
        }
        return UserController::getByName($identifier);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result = User::all();
            if (isset($result[0]["id"])) {
                return (new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                ))->toResponse();
            } else {
                return (new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no user!")
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        return UserController::getById($user)->toResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return static::addNew($request)->toResponse();
    }

    public function login(Request $request)
    {
        $request->validate([
            "remember_token" => ["nullable"],
            "userID" => ['required_without:remember_token'],
            "password" => ['required_without:remember_token'],
        ]);
        try {
            if (isset($request->remember_token)) {
                $remember = $request->remember_token;
                $result = UserController::getByRemember($remember);
                if ($result->getCode() !== ResponseCodes::RESPONSE_OK) {
                    return (new Data(
                        ResponseCodes::ERROR_BAD_REQUEST,
                        new Message("Invalid or expired remember token!")
                    ))->toResponse();
                }
            } else {
                $userID = $request->userID;
                $password = $request->password;
                $result = UserController::getByAny($userID);
                if ($result->getCode() !== ResponseCodes::RESPONSE_OK || !Hash::check($password, $result->getDataRaw()->password)) {
                    return (new Data(
                        ResponseCodes::ERROR_BAD_REQUEST,
                        new Message("Invalid userID or password!")
                    ))->toResponse();
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
            if (isset($request->remember_token)) {
                return (new Data(
                    ResponseCodes::RESPONSE_CREATED,
                    $token
                ))->toResponse();
            }
            $remember = $result->getDataRaw()->remember_token;
            return (new Data(
                ResponseCodes::RESPONSE_CREATED,
                Message::createBundle(
                    new Message($token->user()->name, "userName"),
                    new Message($token->token, "token"),
                    new Message($remember, "remember_token")
                )
            ))->toResponse();
        } catch (Error $e) {
            return (new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message($e->getMessage())
            ))->toResponse();
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $user)
    {
        $result = UserController::getById($user);
        if ($result->getCode() == ResponseCodes::RESPONSE_OK) {
            try {
                if (isset($request["password"])) {
                    $request["password"] = Hash::make($request["password"]);
                }
                $result->getDataRaw()->fill($request->only(["name", "email", "password", "xp"]));
                $result->getDataRaw()->save();
            } catch (Error $e) {
                $result->setCode(ResponseCodes::ERROR_INTERNAL);
                $result->setData(new Message("An internal error occured: " . $e));
            }
        }
        return $result->toResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $user)
    {
        $result = UserController::getById($user);
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
