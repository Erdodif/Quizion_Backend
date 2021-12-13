<?php
namespace Quizion\Backend\Models;
use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use \Error;

class Game extends Model {
    protected $table = "qaming";
    protected $guarded = ["user_id","quiz_id"];
    protected $hidden = ["question_started","right","created_at","updated_at"];

    static public function getName(){
        return "gaming";
    }

    static function newGame($data){
        try {
            $input = json_decode($data->getBody(), true);
            $game = Game::create($input);
            $game->current = 0;
            $game->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $game = new Message($e);
            $code = ERROR_INTERNAL;
        }
        return array("code"=>$code,"out"=>$game);
    }
}
