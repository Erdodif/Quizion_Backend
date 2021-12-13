<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {

    $app->get("/answers", function (Request $request, Response $response) {
        $result = Answer::getAll();
        return $result->withResponse($response);
    });

    $app->group("/answer", function (RouteCollectorProxy $group) {
        $group->post("", function (Request $request, Response $response) {
            $result = Answer::addNew(json_decode($request->getBody(),true));
            return $result->withResponse($response);
        });
        $group->group("/{id}",function(RouteCollectorProxy $group){
            $group->get("", function (Request $request, Response $response, array $args) {
                $results = Answer::getById($args['id']);
                return $results->withResponse($response);
            });
            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Answer::alterById($args["id"],json_decode($request->getBody(), true));
                return $result->withResponse($response);
            });
            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Answer::deleteById($args["id"]);
                return $result->withResponse($response);
            });
        });
    });

    // GET RIGHT answer
    $app->get("/pick/answer/{id}", function (Request $request, Response $response, array $args) {
        $result = Answer::getById($args["id"]);
        $result->getDataRaw()->seeRight();
        return $result->withResponse($response);
    })->add(new AuthMiddleware($app->getResponseFactory()));
};
