<?php
namespace Quizion\Backend\Models;
use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Companion\Message;
use \Error;

class Result extends Model{
    protected $table = "results";
    public $timestamps = false;
    protected $guarded = ["id"];
    
    static function getAll():Data
    {
        try {
            $result = Result::all();
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
}