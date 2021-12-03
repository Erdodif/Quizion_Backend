<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Quiz;
use Quizion\Backend\Models\Question;
use Quizion\Backend\Companion\Message;
use Slim\Routing\RouteCollectorProxy;

return function (Slim\App $app) {
    // GET ALL
    $app->get("/questions", function (Request $request, Response $response) {
        $result = Data::resultFromAll(Question::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });

    $app->group("/question/{id}",function(RouteCollectorProxy $group){
        $group->get("", function (Request $request, Response $response, array $args) {
            $results = Data::resultFromId($args["id"], Question::class);
            $response->getBody()->write($results["out"]->toJson());
            return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
        });

        $group->post("", function (Request $request, Response $response) {
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

        $group->put("", function (Request $request, Response $response, array $args) {
            $result = Data::resultFromId($args["id"], Question::class);
            if ($result["code"] == RESPONSE_OK) {
                $input = json_decode($request->getBody(), true);
                $result["out"]->fill($input);
                $result["out"]->save();
            }
            $response->getBody()->write($result["out"]->toJson());
            return $response->withStatus($result["code"]);
        });
        
        $group->delete("", function (Request $request, Response $response, array $args) {
            $result = Data::resultFromId($args["id"], Question::class);
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
};