<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Quiz;
use Quizion\Backend\Question;
use Quizion\Backend\Answer;
use Quizion\Backend\User;
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

function getUserByName($identifier):array{
    $code = RESPONSE_OK;
    $out = "";
    if (isset($identifier["name"])) {
        $user = User::where("name", "=", $identifier["name"])->get();
        if ($user ===null){
            $out = new Message("User not found");
            $code = ERROR_NOT_FOUND;
        }
        else{
            $out = $user;
            $code = RESPONSE_OK;
        }
    } else {
        $out = new Message("Invalid identifier reference!");
        $code = ERROR_BAD_REQUEST;
    }
    return array("code"=>$code,"out"=>$out);
}

function getUserByEmail($identifier):array{
    $code = RESPONSE_OK;
    $out = "";
    if (isset($identifier["email"])) {
        $user = User::where("email", "=", $identifier["email"])->get();
        if ($user ===null){
            $out = new Message("User not found");
            $code = ERROR_NOT_FOUND;
        }
        else{
            $out = $user;
            $code = RESPONSE_OK;
        }
    } else {
        $out = new Message("Invalid identifier reference!");
        $code = ERROR_BAD_REQUEST;
    }
    return array("code"=>$code,"out"=>$out);
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

function resultFromAll($class): array
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

return function (Slim\App $app) {
    // GET ALL - quizes/questions/answers/users
    $app->get("/quizes", function (Request $request, Response $response) {
        $result = resultFromAll(Quiz::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/questions", function (Request $request, Response $response) {
        $result = resultFromAll(Question::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/answers", function (Request $request, Response $response) {
        $result = resultFromAll(Answer::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/results", function (Request $request, Response $response, array $args) {
        $result = resultFromAll($args["id"], Results::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/users", function (Request $request, Response $response) {
        $result = resultFromAll(User::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    // GET ID - quiz/question/answer/user
    $app->get("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Quiz::class);
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    $app->get("/question/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Question::class);
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    $app->get("/answer/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], Answer::class);
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    $app->get("/user/{id}", function (Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"], User::class);
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });

    // GET BY NAME OR EMAIL - user
    $app->get("/user/{identifier}", function (Request $request, Response $response, array $args) {
        $identifier = $args["identifier"];
        $code = RESPONSE_OK;
        $out = "";
        if (isset($identifier["email"])) {
            $user = User::where("email", "=", $identifier["email"])->get();
            if ($user ===null){
                $out = new Message("User not found");
                $code = ERROR_NOT_FOUND;
            }
            else{
                $out = $user;
                $code = RESPONSE_OK;
            }
        } else if (isset($identifier["name"])) {
            $user = User::where("name", "=", $identifier["name"])->get();
            if ($user ===null){
                $out = new Message("User not found");
                $code = ERROR_NOT_FOUND;
            }
            else{
                $out = $user;
                $code = RESPONSE_OK;
            }
        } else {
            $out = new Message("Invalid identifier reference!");
            $code = ERROR_BAD_REQUEST;
        }
        $response->getBody()->write($out->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
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

    $app->get("/quiz/{id}/question/{question_order}", function (Request $request, Response $response, array $args) {
        $result = getQuestionFromQuiz($args["id"], $args["question_order"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    // GET FROM QUIZ > QUESTION - answers/anwser
    $app->get("/quiz/{id}/question/{question_order}/answers", function (Request $request, Response $response, array $args) {
        $result = getAnswersFromQuiz($args["id"], $args["question_order"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->get("/quiz/{id}/question/{question_order}/answer/{answer_order}", function (Request $request, Response $response, array $args) {
        $result = getAnswerFromQuiz($args["id"], $args["question_order"], $args["answer_order"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    // POST NEW - quiz/question/answer
    $app->post("/quiz", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $quiz = Quiz::create($input);
            $quiz->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $quiz = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($quiz->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    $app->post("/question", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $question = Question::create($input);
            $question->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $question = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($question->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    $app->post("/answer", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $answer = Answer::create($input);
            $answer->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $answer = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($answer->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    $app->post("/user", function (Request $request, Response $response) {
        try {
            $input = json_decode($request->getBody(), true);
            $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
            $user = User::create($input);
            $user->save();
            $code = RESPONSE_CREATED;
        } catch (Error $e) {
            $user = new Message($e);
            $code = ERROR_INTERNAL;
        }
        $response->getBody()->write($user->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    // PUT ID quiz/answer/question/user
    $app->put("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Quiz::class);
        if ($result["code"] == RESPONSE_OK) {
            $input = json_decode($request->getBody(), true);
            $result["out"]->fill($input);
            $result["out"]->save();
        }
        $response->getBody()->write($result["out"]->toJson());
        return $response->withStatus($result["code"]);
    });

    $app->put("/question/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Question::class);
        if ($result["code"] == RESPONSE_OK) {
            $input = json_decode($request->getBody(), true);
            $result["out"]->fill($input);
            $result["out"]->save();
        }
        $response->getBody()->write($result["out"]->toJson());
        return $response->withStatus($result["code"]);
    });

    $app->put("/answer/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Answer::class);
        if ($result["code"] == RESPONSE_OK) {
            $input = json_decode($request->getBody(), true);
            $result["out"]->fill($input);
            $result["out"]->save();
        }
        $response->getBody()->write($result["out"]->toJson());
        return $response->withStatus($result["code"]);
    });

    $app->put("/user/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], User::class);
        if ($result["code"] == RESPONSE_OK) {
            $input = json_decode($request->getBody(), true);
            if (isset($input["password"])) {
                $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
            }
            $result["out"]->fill($input);
            $result["out"]->save();
        }
        $response->getBody()->write($result["out"]->toJson());
        return $response->withStatus($result["code"]);
    });

    // DELETE ID quiz/answer/question/user
    $app->delete("/quiz/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Quiz::class);
        if ($result["code"] == RESPONSE_OK) {
            $result["out"]->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        } else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]->toJson());
        }
        return $response->withStatus($result["code"]);
    });

    $app->delete("/question/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Question::class);
        if ($result["code"] == RESPONSE_OK) {
            $result["out"]->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        } else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]->toJson());
        }
        return $response->withStatus($result["code"]);
    });

    $app->delete("/answer/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], Answer::class);
        if ($result["code"] == RESPONSE_OK) {
            $result["out"]->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        } else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]->toJson());
        }
        return $response->withStatus($result["code"]);
    });

    $app->delete("/user/{id}", function (Request $request, Response $response, array $args) {
        $result = resultFromId($args["id"], User::class);
        if ($result["code"] == RESPONSE_OK) {
            $result["out"]->delete();
            $result["code"] = RESPONSE_NO_CONTENT;
        } else {
            $result["code"] = ERROR_NOT_FOUND;
            $response->getBody()->write($result["out"]->toJson());
        }
        return $response->withStatus($result["code"]);
    });
};
