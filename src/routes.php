<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Quiz;
use Quizion\Backend\Question;
use Quizion\Backend\Answer;
use Quizion\Backend\Message;

require_once "responseCodes.php";

function getQuestionsFromQuiz($quiz_id): array
{
    $actives = Question::where("quiz_id", "=", $quiz_id)->get();
    if (idIsValid($quiz_id)) {
        if (resultFromId($quiz_id, Quiz::class)["code"] == RESPONSE_OK) {
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

function getQuestionFromQuiz($quiz_id, $question_order): array
{
    $result = getQuestionsFromQuiz($quiz_id);
    if ($result["code"] == RESPONSE_OK) {
        if (idIsValid($question_order)) {
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

function getAnswersFromQuiz($quiz_id, $question_order): array
{
    $result = getQuestionFromQuiz($quiz_id, $question_order);
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

function getAnswerFromQuiz($quiz_id, $question_order, $answer_order)
{
    $result = getAnswersFromQuiz($quiz_id, $question_order);
    if ($result["code"] == RESPONSE_OK) {
        if (idIsValid($answer_order)) {
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

function idIsValid($id): bool
{
    return is_numeric($id) && $id > 0;
}

function resultFromId($id, $class): array
{
    try {
        if (!idIsValid($id)) {
            $code = ERROR_BAD_REQUEST;
            $message = '{"message":"Invalid id reference!"}';
        } else {
            $element = $class::find($id);
            if ($element === null) {
                $code = ERROR_NOT_FOUND;
                $message = '{"message":"Resource not found!"}';
            } else {
                $code = RESPONSE_OK;
                $message = json_encode($element);
            }
        }
    } catch (Error $e) {
        $code = ERROR_INTERNAL;
        $message = '{"message" :"An internal error occured!","cause":"' + $e->getMessage() + '"}';
    } finally {
        return array("code" => $code, "out" => $message);
    }
}

return function (Slim\App $app) {

    // GET ALL - quizes/questions/answers
    $app->get("/quizes", function (Request $request, Response $response) {
        $quizes = Quiz::all();
        $out = $quizes->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get("/questions", function (Request $request, Response $response) {
        $question = Question::all();
        $out = $question->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get("/answers", function (Request $request, Response $response) {
        $answers = Answer::all();
        $out = $answers->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });

    // GET ID - quizes/questions/answers
    $app->get("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Quiz::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    $app->get("/question/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Question::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    $app->get("/answer/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Answer::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    // GET ACTIVE - quizes 
    $app->get("/quizes/active", function (Request $request, Response $response) {
        $actives = Quiz::where("active", "=", 1)->get()->toJson();
        if ($actives === "[]") {
            $code = ERROR_NOT_FOUND;
            $response->getBody()->write('{"message":"Empty result!"}');
        } else {
            $response->getBody()->write($actives);
            $code = RESPONSE_OK;
        }
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    // GET FROM QUIZ - questions/question
    $app->get("/quiz/{id}/questions", function (Request $request, Response $response, array $args) {
        $result = getQuestionsFromQuiz($args["id"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/quiz/{id}/question/{number}", function (Request $request, Response $response, array $args) {
        $result = getQuestionFromQuiz($args["id"], $args["number"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    // GET FROM QUIZ > QUESTION - answers/anwser
    $app->get("/quiz/{id}/question/{number}/answers", function (Request $request, Response $response, array $args) {
        $result = getAnswersFromQuiz($args["id"], $args["number"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/quiz/{id}/question/{question_number}/answer/{answer_number}", function (Request $request, Response $response, array $args) {
        $result = getAnswerFromQuiz($args["id"], $args["question_number"], $args["answer_number"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    // POST NEW - quizes/questions/answers
    $app->post("/quizes", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $result = Quiz::create($input);
            $result->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $result = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($result->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    $app->post("/questions", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $rerult = Question::create($input);
            $rerult->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $result = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($result->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    $app->post("/answers", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $result = Answer::create($input);
            $result->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $result = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($result->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    // PUT quiz/answer/question
    $app->put("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $input = json_decode($request->getBody(), true);
        $quiz = Quiz::find($args["id"]);
        $quiz->save();
        $quiz->fill($input);
        $response->getBody()->write($quiz->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(RESPONSE_OK);
    });

    $app->put("/answer/{id}", function (Request $request, Response $response, array $args) {
        $input = json_decode($request->getBody(), true);
        $answer = Answer::find($args["id"]);
        $answer->save();
        $answer->fill($input);
        $response->getBody()->write($answer->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(RESPONSE_OK);
    });

    $app->put("/question/{id}", function (Request $request, Response $response, array $args) {
        $input = json_decode($request->getBody(), true);
        $question = Question::find($args["id"]);
        $question->save();
        $question->fill($input);
        $response->getBody()->write($question->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(RESPONSE_OK);
    });

    // DELETE quiz/answer/question
    $app->delete("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Quiz::class);
        if ($result["code"] == RESPONSE_OK) {
            $quiz = Quiz::find($args["id"]);
            $quiz->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        }
        else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]);
        }
        return $response->withStatus($result["code"]);
    });

    $app->delete("/question/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Question::class);
        if ($result["code"] == RESPONSE_OK) {
            $question = Question::find($args["id"]);
            $question->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        }
        else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]);
        }
        return $response->withStatus($result["code"]);
    });

    $app->delete("/answer/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Answer::class);
        if ($result["code"] == RESPONSE_OK) {
            $answer = Answer::find($args["id"]);
            $answer->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        }
        else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]);
        }
        return $response->withStatus($result["code"]);
    });
};
