<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Answer;
use Quizion\Backend\Question;
use Quizion\Backend\Quiz;

return function(Slim\App $app) {
    $app->get("/quizes", function(Request $request, Response $response) {
        $quizes = Quiz::all();
        $out = $quizes->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get("/questions", function(Request $request, Response $response) {
        $question = Question::all();
        $out = $question->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });





    
    $app->get("/question/{id}", function(Request $request, Response $response, array $args) {
        $question = Question::find($args["id"]);
        $response->getBody()->write($question->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(201);
    });

    $app->get("/answers/{id}", function(Request $request, Response $response, array $args) {
        $answer = Answer::find($args["id"]);
        $response->getBody()->write($answer->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(201);
    });
};
