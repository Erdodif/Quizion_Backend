<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Companion\Data;
use App\Companion\Message;
use \Error;
use Exception;

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

    static function getByQuiz(int $quiz_id):Data
    {
        try {
            $result = Result::where("quiz_id",$quiz_id)->get();
            if (isset($result[0]["id"])) {
                $data = new Data(
                    RESPONSE_OK,
                    $result
                );
            } else {
                $data = new Data(
                    ERROR_NOT_FOUND,
                    new Message("No one played this quiz yet!")
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

    static function saveFromGame(Game $game):Data
    {   
        try{
            $result = Result::firstOrNew(["user_id"=>$game->user_id,"quiz_id"=>$game->quiz_id]);
            $isnew = empty($result->points);
            if($isnew){
                $result->points = $game->right;
                $result->save();
                $data = new Data(
                    RESPONSE_CREATED,
                    new Message("First result by the user.")
                );
            }
            else{
                if($result->points > $game->right){ 
                    $result->points = $game->right;
                    $result->save();
                    $data = new Data(
                        RESPONSE_OK,
                        new Message("New Highscore!")
                    );
                }
                else{
                    $data = new Data(
                        RESPONSE_NOT_MODIFIED,
                        new Message("Worse than last the time...")
                    );
                }
            }
        }
        catch (Exception $e){
            $data = new Data(
                ERROR_INTERNAL,
                new Message("An internal error occured! $e")
            );
        }
        return $data;
    }
}