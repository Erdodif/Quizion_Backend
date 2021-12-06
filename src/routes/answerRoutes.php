<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Companion\Message;
use Slim\Routing\RouteCollectorProxy;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {

    $app->get("/answers", function (Request $request, Response $response) {
        $result = Data::resultFromAll(Answer::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->group("/answer", function (RouteCollectorProxy $group) {
        $group->post("/answer", function (Request $request, Response $response) {
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

        $group->group("/{id}", function (RouteCollectorProxy $group) {
            $group->get("", function (Request $request, Response $response, array $args) {
                $results = Data::resultFromId($args["id"], Answer::class);
                $response->getBody()->write($results["out"]->toJson());
                return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
            });

            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], Answer::class);
                if ($result["code"] == RESPONSE_OK) {
                    $input = json_decode($request->getBody(), true);
                    $result["out"]->fill($input);
                    $result["out"]->save();
                }
                $response->getBody()->write($result["out"]->toJson());
                return $response->withStatus($result["code"]);
            });

            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], Answer::class);
                if ($result["code"] == RESPONSE_OK) {
                    $result["out"]->delete();
                    $result["code"] = RESPONSE_NO_CONTENT;
                } else {
                    $result["code"] = ERROR_NOT_FOUND;
                    $response->getBody()->write($result["out"]->toJson());
                }
                return $response->withStatus($result["code"]);
            });
        });
    });

    // GET RIGHT answer
    $app->get("/pick/answer/{id}", function (Request $request, Response $response, array $args) {
        $results = Data::getAnswerIsRight($args["id"]);
        $response->getBody()->write($results["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });
};
