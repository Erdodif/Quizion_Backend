<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\User;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Models\Token;
use Slim\Routing\RouteCollectorProxy;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $app->get("/users", function (Request $request, Response $response) {
        $result = User::getAll();
        return $result->withResponse($response);
    });
    $app->group("/user", function (RouteCollectorProxy $group) {
        $group->post("/login", function (Request $request, Response $response) {
            $result = Token::addNewByLogin(json_decode($request->getBody(), true));
            return $result->withResponse($response);
        });

        $group->post("/register", function (Request $request, Response $response) {
            $result = User::addNew(json_decode($request->getBody(), true));
            return $result->withResponse($response);
        });

        $group->group("/{userID}", function (RouteCollectorProxy $group) {
            $group->get("", function (Request $request, Response $response, array $args) {
                $result = User::getByAny($args["userID"]);
                return $result->withResponse($response);
            });

            $group->put("", function (Request $request, Response $response, array $args) {
                $result = User::alterById($args["userID"],json_decode($request->getBody(), true));
                return $result->withResponse($response);
            });

            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = User::deleteById($args["userID"]);
                return $result->withResponse($response);
            });
        });
    });
};
