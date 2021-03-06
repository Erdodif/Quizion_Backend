<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Message;
use App\Companion\Data;
use \Error;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Companion\ResponseCodes;
use App\Http\Controllers\API\QuizQuestionController;

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
                return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("No data provided!")
                );
            }
            Data::castArray($input);
            $invalids = Data::inputErrors($input, ["user_id", "quiz_id"]);
            if ($invalids) {
                $out = "";
                foreach (["user_id", "quiz_id"] as $invalid) {
                    $out .= $invalid . ", ";
                }
                $out = substr($out, 0, -2);
                return new Data(
                    ResponseCodes::ERROR_BAD_REQUEST,
                    new Message("Missing " . $out)
                );
            }
            $answer = Game::create($input);
            $answer->save();
            return new Data(
                ResponseCodes::RESPONSE_CREATED,
                $answer
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message($e)
            );
        } catch (Exception $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message($e)
            );
        }
    }

    function setStarted()
    {
        if ($this->question_started == 0 || $this->question_started == false) {
            $this->question_started = 1;
            $this->save();
        }
    }

    function incrementCurrent()
    {
        $this->question_started = 0;
        $this->current = $this->current + 1;
        $this->save();
    }

    function addPoints(int $points)
    {
        $this->right = $this->right + $points;
        $this->save();
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
        if ($this->current > QuizQuestionController::getAllByQuiz($this->quiz_id)->getDataRaw()->count()) {
            $result = Result::saveFromGame($this);
            $this->delete();
        } else {
            $this->setStarted();
            if (!$this->question_started) {
                $this->fill(["started" => true]);
                $this->save();
            }
            $result = QuizQuestionController::getByOrder($this->quiz_id, $this->current);
        }
        return $result;
    }

    function getCurrentAnswers(): Data
    {
        $this->setStarted();
        $question = $this->getCurrentQuestion()->getDataRaw();
        $result = Answer::getAllByQuestion($question->id);
        return $result;
    }

    function getPoints(Collection $picked): int
    {
        $maxPoint = $this->getCurrentQuestion()->getDataRaw()->point;
        $earned = $maxPoint * $this->calculateRatio($picked);
        return $earned;
    }

    function calculateRatio(Collection $picked): float
    {
        $rightAnswerCount = Answer::getRightAnswersCount($this->getCurrentAnswers()->getDataRaw());
        $success = 0;
        $picked->map(function ($pickedElement) use (&$success) {
            if ($pickedElement->is_right == 1) {
                $success++;
            } else if ($pickedElement->is_right == 0) {
                $success--;
            };
        });
        if ($success <= 0) {
            return 0;
        }
        return $success / $rightAnswerCount;
    }

    function pickAnswers(array $picked): Data
    {
        if ($this->current > QuizQuestionController::getAllByQuiz($this->quiz_id)->getDataRaw()->count()) {
            $this->delete();
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Game ended!")
            );
        }
        $started = $this->updated_at;
        $duration = DB::select(DB::raw("SELECT TIMESTAMPDIFF(SECOND, '$started', CURRENT_TIMESTAMP) AS r_now"))[0]->r_now;
        $limit = Quiz::getById($this->quiz_id)->getDataRaw()->seconds_per_quiz;
        if ($this->question_started === 0) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("The question not started!")
            );
        }
        if ($duration > $limit) {
            $this->incrementCurrent();
            return new Data(
                ResponseCodes::ERROR_TIMEOUT,
                new Message("Question timed out!")
            );
        }
        if (empty($picked)) {
            $data = $this->getCurrentAnswers();
            $data->getDataRaw()->map(function ($element) {
                return $element->seeRight();
            });
            $this->incrementCurrent();
            return $data;
        }
        $pickedAnswers = Answer::getByIds($picked);
        if ($pickedAnswers->getCode() !== ResponseCodes::RESPONSE_OK) {
            return new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("One or more given answers do not exist!")
            );
        }
        $pickedAnswers = $pickedAnswers->getDataRaw();
        $question_id = $this->getCurrentQuestion()->getDataRaw()->id;
        $ok = true;
        $pickedAnswers->map(function ($item) use (&$ok, &$question_id) {
            if ($item->question_id !== $question_id) {
                $ok = false;
            }
            return $ok;
        });
        if (!$ok) {
            return new Data(
                ResponseCodes::ERROR_BAD_REQUEST,
                new Message("One or more given answers do not belong to the current question!")
            );
        }
        $points = $this->getPoints($pickedAnswers);
        $this->addPoints($points);
        $data = $this->getCurrentAnswers();
        $data->getDataRaw()->map(function ($element) {
            return $element->seeRight();
        });
        $this->incrementCurrent();
        return $data;
    }

    function getCurrentState(): Data
    {
        return new Data(ResponseCodes::RESPONSE_OK, new Message($this->current, "current", MESSAGE_TYPE_INT));
    }

    function quiz(): Quiz
    {
        return $this->belongsTo(Quiz::class)->where("user_id", $this->user_id)->first();
    }

    function user(): User
    {
        return $this->belongsTo(User::class)->where("quiz_id", $this->quiz_id)->first();
    }
}
