<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use App\Companion\ResponseCodes;

class Quiz extends Table
{
    protected $table = "quiz";
    public $timestamps = false;
    protected $guarded = ["id"];
    protected $hidden = ["active"];

    static function getName(): string
    {
        return "Quiz";
    }
    static function getRequiredColumns(): array
    {
        return ["header", "description", "active"];
    }

    static function getActives(): Data
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
            return $data;
        }
    }
}
