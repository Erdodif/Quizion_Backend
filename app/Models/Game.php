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

    static function getGame(int|Quiz $quiz, int|User $user): Game|false
    {
        if ($quiz instanceof Quiz) {
            $quiz = $quiz->id;
        }
        if ($user instanceof User) {
            $user = $user->id;
        }
        $game = Game::where(["quiz_id" => $quiz, "user_id" => $user])->get();
        if (!isset($game->user_id)) {
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
        $result = Question::getByOrder($this->quiz_id, $this->current);
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

    function getPoints(Collection $picked, int $rightAnswerCount)
    {
        $maxPoint = $this->getCurrentQuestion()->point;
        return $maxPoint * Game::calculateRatio($picked, $rightAnswerCount);
    }

    static function calculateRatio(Collection $picked, int $rightAnswerCount)
    {
        $success = 0;
        $picked->map(function ($pickedElement) use ($success) {
            if ($pickedElement->is_right == 1) {
                $success++;
            } else if ($pickedElement->is_right == 0) {
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
        $rightCount = Answer::getRightAnswersCount($this->getCurrentAnswers());
        $pickedAnswers = Answer::getByIds($picked)->getDataRaw();
        $points = $this->getPoints($pickedAnswers, $rightCount);
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
