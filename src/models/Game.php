<?php

namespace Quizion\Backend\Models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Model;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Question;
use \Error;
use Illuminate\Database\Eloquent\Collection;

class Game extends Model
{
    protected $table = "qaming";
    protected $guarded = ["user_id", "quiz_id"];
    protected $hidden = ["question_started", "right", "created_at", "updated_at"];

    static function newGame(array|Request $input): Data
    {
        try {
            if ($input instanceof Request){
                $input = json_decode($input->getBody(), true);
            }
            $game = Game::create($input);
            $game->current = 0;
            $game->save();
            $data = new Data(
                RESPONSE_CREATED,
                $game
            );
        } catch (Error $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
            );
        }
        return $data;
    }

    static function getGame(int|Quiz $quiz,int|User $user):Game|false
    {
        if($quiz instanceof Quiz){
            $quiz = $quiz->id;
        }
        if($user instanceof User){
            $user = $user->id;
        }
        $game = Game::where(["quiz_id"=>$quiz,"user_id"=>$user])->get();
        if(!isset($game->id)){
           $game = false; 
        }        
        return $game;
    }

    function getCurrentQuestion(): Question|false
    {
        if (!$this->question_started) {
            $this->fill(["started" => true]);
            $this->save();
        }
        $result = Question::getByQuiz($this->current);
        if ($result->getCode() === RESPONSE_OK) {
            $out = $result->getDataRaw();
        } else {
            $out = false;
        }
        return $out;
    }

    function getCurrentAnswers(): Collection|false
    {
        $question = $this->getCurrentQuestion();
        $result = Answer::getAllByQuestion($question->id);
        if ($result->getCode() === RESPONSE_OK) {
            $out = $result->getDataRaw();
        } else {
            $out = false;
        }
        return $out;
    }

    function getPoints(Collection $picked)
    {
        $maxPoint = $this->getCurrentQuestion()->point;
        $answers = $this->getCurrentAnswers();
        return $maxPoint * Game::calculateRatio($picked, $answers);
    }

    static function calculateRatio(Collection $picked, Collection $right)
    {
        $found = 0;
        $right->map(function ($rightElement) use ($picked, $found) {
            $picked->map(function ($pickedElement) use ($rightElement, $found) {
                if ($rightElement->id == $pickedElement->id) {
                    if ($rightElement->is_right == 1 && $pickedElement->is_right == 1) {
                        $found++;
                    } else if ($rightElement->is_right == 1 && $pickedElement->is_right == 0) {
                        $found--;
                    }
                }
            });
        });
        if ($found < 0) {
            $found = 0;
        }
        return $right->count() / $found;
    }

    function pickAnswers(array $picked): Data
    {
        $answers = Answer::getByIds($picked)->getDataRaw();
        $points = $this->getPoints($answers) / 100;
        $this->fill([
            "right" => ($this->right + $points),
            "question_started" => false,
            "current" => ($this->current + 1)
        ]);
        $this->save();
        $answers = Answer::getAllByQuestion($this->getCurrentQuestion()->id);
        $answers->getDataRaw()->map(function ($element) {
            return $element->seeRight();
        });
        return $answers;
    }
}
