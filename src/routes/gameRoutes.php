<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\User;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Middlewares\AuthMiddleware;
use Quizion\Backend\Models\Game;
use Slim\Routing\RouteCollectorProxy;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $app->group("/play",function(RouteCollectorProxy $group){
        //TODO #16 TESZTELÉS
        $group->post("/newgame/{quiz_id}",function(Request $request, Response $response, array $args){
            $userID = $request->getAttribute("userID");
            $game = Game::newGame(array("quiz_id"=>$args["quiz_id"],"user_id"=>$userID));
            return $game->withResponse($response);
        });
        $group->group("/{quiz_id}",function(RouteCollectorProxy $group){

            $group->get("",function(Request $request, Response $response, array $args){
                $game = Game::getGame($args["quiz_id"],$request->getAttribute("userID"));
                $game->getCurrentQuestion();
                return $game->withResponse($response);
            });    

            $group->get("/answers",function(Request $request, Response $response, array $args){
                $game = Game::getGame($args["quiz_id"],$request->getAttribute("userID"));
                $game->getCurrentAnswers();
                return $game->withResponse($response);
            });

            $group->post("",function(Request $request, Response $response, array $args){
                $game = Game::getGame($args["quiz_id"],$request->getAttribute("userID"));
                $game->pickAnswers(json_decode($request->getBody(), true)["answers"]);
                return $game->withResponse($response);
            });
        });
    })->add(new AuthMiddleware($app->getResponseFactory()));
};
