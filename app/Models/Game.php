<?php

namespace App\Models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use App\Models\Question;
use \Error;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Game extends Table
{
    protected $table = "gaming";
    protected $fillable = ["user_id", "quiz_id"];
    protected $hidden = ["question_started", "right", "created_at", "updated_at"];
    static function getName(): string
    {
        return "Game";
    }
    static function getRequiredColumns(): array
    {
        return ["user_id", "quiz_id"];
    }

    function setStarted()
    {
        //nem változtat az adatbázis elemen, nem tudom, miért
        if ($this->question_started == 0) {
            $this->fill(["question_started" => 1]);
            $this->save();
        }
    }

    static function getGame(int|Quiz $quiz, int|User $user): Game|false
    {
        if ($quiz instanceof Quiz) {
            $quiz = $quiz->id;
        }
        if ($user instanceof User) {
            $user = $user->id;
        }
        $game = Game::where(["quiz_id" => $quiz, "user_id" => $user])->first();
        if (!isset($game->user_id)) {
            $game = false;
        }
        return $game;
    }

    function getCurrentQuestion(): Data
    {
        $this->setStarted();
        if (!$this->question_started) {
            $this->fill(["started" => true]);
            $this->save();
        }
        $result = Question::getByOrder($this->quiz_id, $this->current);
        return $result;
    }

    function getCurrentAnswers(): Data
    {
        $this->setStarted();
        $question = $this->getCurrentQuestion()->getDataRaw();
        $result = Answer::getAllByQuestion($question->id);
        return $result;
    }

    function getPoints(Collection $picked)
    {
        $maxPoint = $this->getCurrentQuestion()->point;
        return $maxPoint * $this->calculateRatio($picked);
    }

    function calculateRatio(Collection $picked)
    {
        $rightAnswerCount = Answer::getRightAnswersCount($this->getCurrentAnswers()->getDataRaw());
        $question_id = $this->getCurrentQuestion()->id;
        $success = 0;
        $picked->map(function ($pickedElement) use ($success, $question_id) {
            if ($pickedElement->question_id == $question_id) {
                if ($pickedElement->is_right == 1) {
                    $success++;
                } else if ($pickedElement->is_right == 0) {
                    $success--;
                }
            } else {
                $success--;
            }
        });
        if ($success <= 0) {
            $success = 0;
        } else {
            $success = $success / $rightAnswerCount;
        }
        return $success;
    }

    function pickAnswers(array $picked): Data
    {
        $started = $this->updated_at;
        $duration = DB::select(DB::raw("SELECT TIMESTAMPDIFF(SECOND,'$started', CURRENT_TIMESTAMP) AS r_now"))[0]->r_now;
        $limit = Quiz::getById($this->quiz_id)->seconds_per_quiz;
        if ($duration > $limit) {
            $this->fill([
                "question_started" => false,
                "current" => ($this->current + 1)
            ]);
            $this->save();
            $data = new Data(
                ERROR_TIMEOUT,
                new Message("Question timed out!")
            );
        } else {
            $pickedAnswers = Answer::getByIds($picked)->getDataRaw();
            $points = $this->getPoints($pickedAnswers);
            $this->fill([
                "right" => ($this->right + $points),
                "question_started" => false,
                "current" => ($this->current + 1)
            ]);
            $this->save();
            $data = $this->getCurrentAnswers();
            $data->getDataRaw()->map(function ($element) {
                return $element->seeRight();
            });
        }
        return $data;
    }
}
