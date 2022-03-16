<?php

namespace App\Http\Controllers\API;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Error;
use Illuminate\Http\Request;

class UserSetAdminController extends Controller
{
    public function index()
    {
        try {
            $result = Admin::all();
            if (isset($result[0]["id"])) {
                return (new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                ))->toResponse();
            } else {
                return (new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no admin!")
                ))->toResponse();
            }
        } catch (Error $e) {
            return (new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            ))->toResponse();
        }
    }

    public function grantPrivilege($user)
    {
        $result = UserController::getById($user);
        if ($result->getCode() < 400) {
            if (Admin::isAdmin($user)) {
                return (new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("The user has already got admin privileges!")
                ))->toResponse();
            }
            Admin::create(["user_id" => $user]);
            return (new Data(
                ResponseCodes::RESPONSE_NO_CONTENT,
                null
            ))->toResponse();
        }
        return $result->toResponse();
    }

    public function revokePrivilege(Request $request,$user)
    {
        $result = UserController::getById($user);
        if ($result->getCode() < 400) {
            if (!Admin::isAdmin($user)) {
                return (new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("The user is not an admin!")
                ))->toResponse();
            }
            if($user == $request->userID){
                return (new Data(
                    ResponseCodes::ERROR_FORBIDDEN,
                    new Message("Cannot revoke your own privileges!")
                ))->toResponse();
            }
            Admin::where("user_id",$user)->delete();
            return (new Data(
                ResponseCodes::RESPONSE_NO_CONTENT,
                null
            ))->toResponse();
        }
        return $result->toResponse();
    }
}
