<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Companion\Data;
use App\Companion\Message;
use \Error;

class Result extends Model{
    protected $table = "results";
    public $timestamps = false;
    protected $guarded = ["id"];
    
    static function getAll():Data
    {
        try {
            $result = Result::all();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("There is no result!")
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