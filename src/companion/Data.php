<?php

namespace Quizion\Backend\Companion;

use Quizion\Backend\Models\Quiz;
use Quizion\Backend\Models\Question;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Models\User;
use Quizion\Backend\Models\Result;
use \Error;

require_once "responseCodes.php";

class Data
{
    static function getActiveQuizes(): array
    {
        $actives = Quiz::where("active", "=", 1)->get();
        if ($actives === "[]") {
            $code = ERROR_NOT_FOUND;
            $response = new Message("Empty result!");
        } else {
            $response = $actives;
            $code = RESPONSE_OK;
        }
        return array("code" => $code, "out"=> $response);
    }

    static function getQuestionsFromQuiz($quiz_id): array
    {
        if (Data::idIsValid($quiz_id)) {
            $actives = Question::where("quiz_id", "=", $quiz_id)->get();
            if (Data::resultFromId($quiz_id, Quiz::class)["code"] == RESPONSE_OK) {
                if ($actives === null || empty($actives)) {
                    $response = new Message("Empty result!");
                    $code = ERROR_NOT_FOUND;
                } else {
                    $response = $actives;
                    $code = RESPONSE_OK;
                }
            } else {
                $response = new Message("Quiz #$quiz_id not found!");
                $code = ERROR_NOT_FOUND;
            }
        } else {
            $response = new Message("Invalid quiz reference!");
            $code = ERROR_BAD_REQUEST;
        }
        return array("code" => $code, "out" => $response);
    }

    static function getQuestionFromQuiz($quiz_id, $question_order): array
    {
        $result = Data::getQuestionsFromQuiz($quiz_id);
        if ($result["code"] == RESPONSE_OK) {
            if (Data::idIsValid($question_order)) {
                if ($result["out"] === null || empty($result["out"]) || !isset($result["out"][$question_order - 1])) {
                    $response = new Message("$quiz_id. quiz does not have $question_order . question!");
                    $code = ERROR_NOT_FOUND;
                } else {
                    $active = $result["out"][$question_order - 1];
                    $response = $active;
                    $code = RESPONSE_OK;
                }
            } else {
                $response = new Message("Invalid question number reference!");
                $code = ERROR_BAD_REQUEST;
            }
        } else {
            $response = $result["out"];
            $code = $result["code"];
        }
        return array("code" => $code, "out" => $response);
    }

    static function getAnswersFromQuestion($question_id):array{
        if (Data::idIsValid($question_id)) {
            $actives = Answer::where("question_id", "=", $question_id)->get();
            if (Data::resultFromId($question_id, Question::class)["code"] == RESPONSE_OK) {
                if ($actives === null || empty($actives)) {
                    $response = new Message("Question #$question_id has no answers!");
                    $code = ERROR_NOT_FOUND;
                } else {
                    $response = $actives;
                    $code = RESPONSE_OK;
                }
            } else {
                $response = new Message("Question #$question_id not found!");
                $code = ERROR_NOT_FOUND;
            }
        } else {
            $response = new Message("Invalid question reference!");
            $code = ERROR_BAD_REQUEST;
        }
        return array("code" => $code, "out" => $response);
    }

    static function getAnswerFromQuestion($question_id,$answer_order):array{
        $result = Data::getAnswersFromQuestion($question_id);
        if ($result["code"] == RESPONSE_OK) {
            if (Data::idIsValid($answer_order)) {
                if ($result["out"] === null || empty($result["out"]) || !isset($result["out"][$answer_order - 1])) {
                    $response = new Message("Question #$question_id does not have $answer_order. answer!");
                    $code = ERROR_NOT_FOUND;
                } else {
                    $active = $result["out"][$answer_order - 1];
                    $response = $active;
                    $code = RESPONSE_OK;
                }
            } else {
                $response = new Message("Invalid answer number reference!");
                $code = ERROR_BAD_REQUEST;
            }
        } else {
            $response = $result["out"];
            $code = $result["code"];
        }
        return array("code" => $code, "out" => $response);
    }

    static function getAnswersFromQuiz($quiz_id, $question_order): array
    {
        $result = Data::getQuestionFromQuiz($quiz_id, $question_order);
        if ($result["code"] == RESPONSE_OK) {
            if (isset($result["out"]["id"])) {
                $question_id = $result["out"]["id"];
                $response = Answer::where("question_id", "=", $question_id)->get();
                $code = RESPONSE_OK;
            } else {
                $response = new Message("Empty result!");
                $code = ERROR_NOT_FOUND;
            }
        } else {
            $response = $result["out"];
            $code = $result["code"];
        }
        return array("code" => $code, "out" => $response);
    }

    static function getAnswerFromQuiz($quiz_id, $question_order, $answer_order)
    {
        $result = Data::getAnswersFromQuiz($quiz_id, $question_order);
        if ($result["code"] == RESPONSE_OK) {
            if (Data::idIsValid($answer_order)) {
                if (isset($result["out"][$answer_order - 1])) {
                    $response = $result["out"][$answer_order - 1];
                    $code = RESPONSE_OK;
                } else {
                    $response = new Message("Question #$question_order does not have $answer_order. answer!");
                    $code = ERROR_NOT_FOUND;
                }
            } else {
                $response = new Message("Invalid answer order reference");
                $code = ERROR_BAD_REQUEST;
            }
        } else {
            $response = $result["out"];
            $code = $result["code"];
        }
        return array("code" => $code, "out" => $response);
    }

    static function getRightAnswersFromQuiz($quiz_id, $question_order): array
    {
        $results = Data::getAnswersFromQuiz($quiz_id, $question_order);
        if ($results["code"] == RESPONSE_OK) {
            $results["out"]->makeVisible(["is_right"]);
            $results["out"]->makeHidden(["content"]);
        }
        return array("code" => $results["code"], "out" => $results["out"]);
    }

    static function getUserByName($identifier): array
    {
        $code = RESPONSE_OK;
        $out = "";
        if (isset($identifier)) {
            $user = User::where("name", "=", $identifier)->get();
            if (!isset($user[0])) {
                $out = new Message("User not found");
                $code = ERROR_NOT_FOUND;
            } else {
                $out = $user[0];
                $code = RESPONSE_OK;
            }
        } else {
            $out = new Message("Invalid identifier reference!");
            $code = ERROR_BAD_REQUEST;
        }
        return array("code" => $code, "out" => $out);
    }

    static function getUserByEmail($identifier): array
    {
        $code = RESPONSE_OK;
        $out = "";
        if (isset($identifier)) {
            $user = User::where("email", "=", $identifier)->get();
            if (!isset($user[0])) {
                $out = new Message("User not found");
                $code = ERROR_NOT_FOUND;
            } else {
                $out = $user[0];
                $code = RESPONSE_OK;
            }
        } else {
            $out = new Message("Invalid identifier reference!");
            $code = ERROR_BAD_REQUEST;
        }
        return array("code" => $code, "out" => $out);
    }

    static function idIsValid($id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    static function resultFromId($id, $class): array
    {
        try {
            if (!Data::idIsValid($id)) {
                $code = ERROR_BAD_REQUEST;
                $message = new Message("Invalid id reference!");
            } else {
                $element = $class::find($id);
                if (!isset($element["id"])) {
                    $code = ERROR_NOT_FOUND;
                    $message = new Message("Resource not found!");
                } else {
                    $code = RESPONSE_OK;
                    $message = $element;
                }
            }
        } catch (Error $e) {
            $code = ERROR_INTERNAL;
            $message = new Message("An internal error occured! cause: " . $e->getMessage());
        } finally {
            return array("code" => $code, "out" => $message);
        }
    }

    static function resultFromAll($class): array
    {
        $out = null;
        try {
            $result = $class::all();
            if (isset($result[0]["id"])) {
                $out = $result;
                $code = RESPONSE_OK;
            } else {
                $out = new Message("There is no " . $class::getName() . "!");
                $code = ERROR_NOT_FOUND;
            }
        } catch (Error $e) {
            $out = new Message("An Internal error occured! " . $e);
            $code = ERROR_INTERNAL;
        } finally {
            return array("code" => $code, "out" => $out);
        }
    }
}
