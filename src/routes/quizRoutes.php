<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Quiz;
use Quizion\Backend\Companion\Message;
use Slim\Routing\RouteCollectorProxy;

return function (Slim\App $app) {
    // GET ALL
    $app->group("/quizes",function(RouteCollectorProxy $group){
        $group->get("", function (Request $request, Response $response, array $args) {
            $results = Data::resultFromAll(Quiz::class);
            $response->getBody()->write($results["out"]->toJson());
            return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
        });
        // GET ACTIVE - quizes //nincs kiemelve!!
        $group->get("/active", function (Request $request, Response $response) {
            $results = Data::getActiveQuizes();
            $response->getBody()->write($results["out"]->toJson());
            return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
        });
    });
    
    $app->group("/quiz",function(RouteCollectorProxy $group){
        $group->post("", function (Request $request, Response $response) {
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
        $group->group("/{id}",function(RouteCollectorProxy $group){
            $group->get("", function (Request $request, Response $response, array $args) {
                $results = Data::resultFromId($args["id"], Quiz::class);
                $response->getBody()->write($results["out"]->toJson());
                return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
            });
            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], Quiz::class);
                if ($result["code"] == RESPONSE_OK) {
                    $input = json_decode($request->getBody(), true);
                    $result["out"]->fill($input);
                    $result["out"]->save();
                }
                $response->getBody()->write($result["out"]->toJson());
                return $response->withStatus($result["code"]);
            });
            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], Quiz::class);
                if ($result["code"] == RESPONSE_OK) {
                    $result["out"]->delete();
                    $result["code"] = RESPONSE_NO_CONTENT;
                } else {
                    $result["code"] = ERROR_NOT_FOUND;
                    $response->getBody()->write($result["out"]->toJson());
                }
                return $response->withStatus($result["code"]);
            });
            
            // GET FROM QUIZ - questions/question
            $group->get("/questions", function (Request $request, Response $response, array $args) {
                $result = Data::getQuestionsFromQuiz($args["id"]);
                $response->getBody()->write($result["out"]->toJson());
                return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
            });
            $group->group("/question/{question_order}",function(RouteCollectorProxy $group){
                $group->get("", function (Request $request, Response $response, array $args) {
                    $result = Data::getQuestionFromQuiz($args["id"], $args["question_order"]);
                    $response->getBody()->write($result["out"]->toJson());
                    return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
                });

                // GET FROM QUIZ > QUESTION - answers/anwser
                $group->get("/answers", function (Request $request, Response $response, array $args) {
                    $result = Data::getAnswersFromQuiz($args["id"], $args["question_order"]);
                    $response->getBody()->write($result["out"]->toJson());
                    return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
                });

                $group->get("/answer/{answer_order}", function (Request $request, Response $response, array $args) {
                    $result = Data::getAnswerFromQuiz($args["id"], $args["question_order"], $args["answer_order"]);
                    $response->getBody()->write($result["out"]->toJson());
                    return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
                });
            });
        });
    });
    
    //GET RIGHT - aswers
    $app->get("/pick/quiz/{id}/question/{question_order}", function (Request $request, Response $response, array $args) {
        $result = Data::getRightAnswersFromQuiz($args["id"], $args["question_order"]);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
};