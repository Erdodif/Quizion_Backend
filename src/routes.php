<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Quiz;
use Quizion\Backend\Question;
use Quizion\Backend\Answer;

return function(Slim\App $app) {
    // GET quizes questions answers
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
    $app->get("/answers", function(Request $request, Response $response) {
        $answers = Answer::all();
        $out = $answers->toJson();
        $response->getBody()->write($out);
        return $response->withHeader("Content-Type", "application/json");
    });

    // GET ID quizes questions answers
    $app->get("/quiz/{id}", function(Request $request, Response $response, array $args) {
        $quiz = Quiz::find($args["id"]);
        $response->getBody()->write($quiz->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus(201);
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

    // POST quizes questions answers
    $app->post("/quizes", function(Request $request, Response $response) {
        $input = json_decode($request->getBody(), true);
        $quiz = Quiz::create($input);
        $quiz->save();
        $kimenet = $quiz->toJson();
        $response->getBody()->write($kimenet);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    });
    $app->post("/questions", function(Request $request, Response $response) {
        $input = json_decode($request->getBody(), true);
        $question = Question::create($input);
        $question->save();
        $kimenet = $question->toJson();
        $response->getBody()->write($kimenet);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    });
    $app->post("/answers", function(Request $request, Response $response) {
        $input = json_decode($request->getBody(), true);
        $quiz = Quiz::create($input);
        $quiz->save();
        $kimenet = $quiz->toJson();
        $response->getBody()->write($kimenet);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    });
};
