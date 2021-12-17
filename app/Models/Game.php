<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use App\Models\Question;
use \Error;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    protected $guarded = ["id"];
    public $increamenting = false;
    protected $table = "gaming";
    protected $fillable = ["user_id", "quiz_id"];
    protected $hidden = ["question_started", "right", "created_at", "updated_at"];

    static function addNew(array|string|null $input): Data
    {
        try {
            if ($input === null) {
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            } else {
                $invalids = Data::inputErrors($input, ["user_id", "quiz_id"]);
                $input = Data::castArray($input);
                if (!$invalids) {
                    echo var_dump($input);
                    $answer = Game::create($input);
                    $answer->save();
                    $data = new Data(
                        RESPONSE_CREATED,
                        $answer
                    );
                } else {
                    $out = "";
                    foreach (["user_id", "quiz_id"] as $invalid) {
                        $out .= $invalid . ", ";
                    }
                    $out = substr($out, 0, -2);
                    $data = new Data(
                        ERROR_BAD_REQUEST,
                        new Message("Missing " . $out)
                    );
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_BAD_REQUEST,
                new Message($e)
            );
        } catch (Exception $e) {
            $data = new Data(
                ERROR_INTERNAL,
                new Message($e)
            );
        } finally {
            return $data;
        }
    }
    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     *//*
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }*/

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     *//** *//*
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }*/

    function setStarted()
    {
        //nem változtat az adatbázis elemen, nem tudom, miért
        if ($this->question_started == 0 || $this->question_started == false) {
            $this->question_started = 1;
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
