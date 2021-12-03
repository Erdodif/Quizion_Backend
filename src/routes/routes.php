<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Quiz;
use Quizion\Backend\Models\Question;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Models\User;
use Quizion\Backend\Companion\Message;
require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $quizRoutes = require_once "src/routes/quizRoutes.php";
    $quizRoutes($app);
    $questionRoutes = require_once "src/routes/questionRoutes.php";
    $questionRoutes($app);
    $answerRoutes = require_once "src/routes/answerRoutes.php";
    $answerRoutes($app);
    $app->get("/results", function (Request $request, Response $response, array $args) {
        $result = Data::resultFromAll($args["id"], Results::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
    $app->get("/users", function (Request $request, Response $response) {
        $result = Data::resultFromAll(User::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
    $app->get("/user/{identifier}", function (Request $request, Response $response, array $args) {
        $identifier = $args["identifier"];
        if (is_numeric($identifier)) {
            $results = Data::resultFromId($args["identifier"], User::class);
        } else if (str_contains($identifier, "@")) {
            $results = Data::getUserByEmail($identifier);
        } else {
            $results = Data::getUserByName($identifier);
        }
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
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
    $app->put("/user/{id}", function (Request $request, Response $response, array $args) {
        $result = Data::resultFromId($args["id"], User::class);
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
    $app->delete("/user/{id}", function (Request $request, Response $response, array $args) {
        $result = Data::resultFromId($args["id"], User::class);
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
