<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Companion\Data;
use App\Companion\Message;
use \Error;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Companion\ResponseCodes;

class Result extends Model
{
    protected $table = "results";
    public $timestamps = false;
    protected $guarded = ["id"];

    static function getRankingsAll(int $quiz_id): Data
    {
        if (Quiz::getById($quiz_id)->getCode() == ResponseCodes::RESPONSE_OK) {
            DB::statement(DB::raw('set @c=0'));
            $result = collect(
                DB::select(
                    "
                select `results`.`user_id`,`users`.`name`,`results`.`points`, ranks.rank
                from
                `results`
                JOIN
                (
                    select _ranking.*, @c:=@c+1 as rank from
                    (
                        select `quiz_id`, `points`
                        from results
                        where quiz_id = $quiz_id
                        group by `points`
                        order by `points` desc
                    ) as _ranking
                ) as ranks
                ON ranks.quiz_id = `results`.`quiz_id` and ranks.points = `results`.`points`
                JOIN `users`
                ON `users`.`id` = `results`.`user_id` 
                ORDER BY ranks.rank"
                )
            );
            if ($result->isEmpty()) {
                $data = new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There are no scores for this quiz yet.")
                );
            } else {
                $data = new Data(
                    ResponseCodes::RESPONSE_OK,
                    $result
                );
            }
        } else {
            $data = new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("Quiz not found!")
            );
        }
        return $data;
    }

    static function getUserRanking(int $quiz_id, int $user_id): Data
    {
        DB::statement(DB::raw('set @c=0'));
        $result = collect(
            DB::select(
                "
            select `results`.`quiz_id`,`results`.`user_id`,`users`.`name`,`results`.`points`, ranks.rank
            from
            `results`
            JOIN
            (
                select _ranking.*, @c:=@c+1 as rank from
                (
                    select `quiz_id`, `points`
                    from results
                    where quiz_id = $quiz_id
                    group by `points`
                    order by `points` desc
                ) as _ranking
            ) as ranks
            ON ranks.quiz_id = `results`.`quiz_id` and ranks.points = `results`.`points`
            JOIN `users`
            ON `users`.`id` = `results`.`user_id`
            WHERE `results`.`user_id` = $user_id"
            )
        )->first();
        try {
            $result = json_encode($result);
            if ($result === "null") {
                throw new Error("Score not found!");
            }
            $data = new Data(
                ResponseCodes::RESPONSE_OK,
                new Message($result, "users", MESSAGE_TYPE_RAW)
            );
        } catch (Exception | Error $e) {
            $data = new Data(
                ResponseCodes::ERROR_NOT_FOUND,
                new Message("User #$user_id didn't score on this quiz!")
            );
        } finally {
            return $data;
        }
    }

    static function getAll(): Data
    {
        try {
            $result = Result::all();
            if (!isset($result[0]["id"])) {
                return new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("There is no result!")
                );
            }
            return new Data(
                ResponseCodes::RESPONSE_OK,
                $result
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        }
    }

    static function getByQuiz(int $quiz_id): Data
    {
        try {
            $result = Result::where("quiz_id", $quiz_id)->get();
            if (isset($result[0]["id"])) {
                return new Data(
                    ResponseCodes::ERROR_NOT_FOUND,
                    new Message("No one played this quiz yet!")
                );
            }
            return new Data(
                ResponseCodes::RESPONSE_OK,
                $result
            );
        } catch (Error $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! " . $e->getMessage())
            );
        }
    }

    static function saveFromGame(Game $game): Data
    {
        try {
            $result = Result::firstOrNew(["user_id" => $game->user_id, "quiz_id" => $game->quiz_id]);
            $isnew = empty($result->points);
            if ($isnew) {
                $result->points = $game->right;
                $result->save();
                return new Data(
                    ResponseCodes::RESPONSE_CREATED,
                    new Message("First result by the users.", "result")
                );
            }
            if ($result->points < $game->right) {
                $result->points = $game->right;
                $result->save();
                return new Data(
                    ResponseCodes::RESPONSE_OK,
                    new Message("New Highscore!", "result")
                );
            }
            if ($result->points = $game->right) {
                return new Data(
                    ResponseCodes::RESPONSE_OK,
                    new Message("Same result as the last time...", "result")
                );
            }
            return new Data(
                ResponseCodes::RESPONSE_OK,
                new Message("Worse than last the time...", "result")
            );
        } catch (Exception $e) {
            return new Data(
                ResponseCodes::ERROR_INTERNAL,
                new Message("An internal error occured! $e")
            );
        }
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
