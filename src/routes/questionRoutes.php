<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Question;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Models\Answer;
use Slim\Routing\RouteCollectorProxy;

return function (Slim\App $app) {
    // GET ALL
    $app->get("/questions", function (Request $request, Response $response) {
        $result = Question::getAll();
        return $result->withResponse($response);
    });

    $app->group("/question",function(RouteCollectorProxy $group){
        $group->post("", function (Request $request, Response $response) {
            $result = Question::addNew(json_decode($request->getBody(),true));
            return $result->withResponse($response);
        });
        $group->group("/{id}",function(RouteCollectorProxy $group){
            $group->get("", function (Request $request, Response $response, array $args) {
                $results = Question::getById($args['id']);
                return $results->withResponse($response);
            });
            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Question::alterById($args["id"],json_decode($request->getBody(), true));
                return $result->withResponse($response);
            });
            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Question::deleteById($args["id"]);
                return $result->withResponse($response);
            });
            // GET FROM QUIZ > QUESTION - answers/anwser
            $group->get("/answers", function (Request $request, Response $response, array $args) {
                $result = Answer::getAllByQuestion($args["id"]);
                return $result->withResponse($response);
            });

            $group->get("/answer/{answer_order}", function (Request $request, Response $response, array $args) {
                $result = Answer::getByQuestion($args["id"], $args["answer_order"]);
                return $result->withResponse($response);
            });
        });
        
    });
};