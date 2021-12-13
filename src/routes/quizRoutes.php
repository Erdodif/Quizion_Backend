<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Quiz;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Middlewares\AuthMiddleware;
use Quizion\Backend\Models\Answer;
use Quizion\Backend\Models\Question;
use Slim\Routing\RouteCollectorProxy;

return function (Slim\App $app) {
    // GET ALL
    $app->group("/quizes", function (RouteCollectorProxy $group) {
        $group->get("", function (Request $request, Response $response, array $args) {
            $results = Quiz::getAll();
            return $results->withResponse($response);
        });
        // GET ACTIVE - quizes //nincs kiemelve!!
        $group->get("/active", function (Request $request, Response $response) {
            $results = Quiz::getActives();
            return $results->withResponse($response);
        });
    });

    $app->group("/quiz", function (RouteCollectorProxy $group) {
        $group->post("", function (Request $request, Response $response) {
            $results = Quiz::addNew(json_decode($request->getBody(), true));
            return $results->withResponse($response);
        });
        $group->group("/{id}", function (RouteCollectorProxy $group) {
            $group->get("", function (Request $request, Response $response, array $args) {
                $results = Quiz::getById($args['id']);
                return $results->withResponse($response);
            });
            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Quiz::alterById($args["id"], json_decode($request->getBody(), true));
                return $result->withResponse($response);
            });
            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Quiz::deleteById($args["id"]);
                return $result->withResponse($response);
            });

            // GET FROM QUIZ - questions/question
            $group->get("/questions", function (Request $request, Response $response, array $args) {
                $result = Question::getByQuiz($args["id"]);
                return $result->withResponse($response);
            });
            $group->group("/question/{question_order}", function (RouteCollectorProxy $group) {
                $group->get("", function (Request $request, Response $response, array $args) {
                    $result = Question::getByOrder($args["id"],$args["question_order"]);
                    return $result->withResponse($response);
                });

                // GET FROM QUIZ > QUESTION - answers/anwser
                $group->get("/answers", function (Request $request, Response $response, array $args) {
                    $result = Answer::getAllByQuiz($args["id"], $args["question_order"]);
                    return $result->withResponse($response);
                });

                $group->get("/answer/{answer_order}", function (Request $request, Response $response, array $args) {
                    $result = Answer::getByQuiz($args["id"], $args["question_order"], $args["answer_order"]);
                    return $result->withResponse($response);
                });
            });
        });
    });

    //GET RIGHT - aswers
    $app->get("/pick/quiz/{id}/question/{question_order}", function (Request $request, Response $response, array $args) {
        $result = Answer::getAllByQuiz($args["id"], $args["question_order"]);
        $result->getDataRaw()->seeRight();
        return $result->withResponse($response);
    })->add(new AuthMiddleware($app->getResponseFactory()));
};
