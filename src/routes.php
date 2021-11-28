<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Quiz;
use Quizion\Backend\Question;
use Quizion\Backend\Answer;

function resultFromId($id,$class) :array{
    try{
        if(!is_numeric($id) || $id < 1){
            $code = 400;
            $message = '{"message":"Invalid id reference!"}';
        }
        else{
            $element = $class::find($id);
            if($element === null){
                $code = 404;
                $message = '{"message":"Resource not found!"}';
            }
            else{
                $code = 200;
                $message = json_encode($element);
            }
        }
    }
    catch (Error $e){
        $code = 500;
        $message = '{"message" :"An internal error occured!","cause":"'+$e->getMessage()+'"}';
    }
    finally{
        return array("code"=>$code,"out"=>$message);
    }
}

return function(Slim\App $app) {
    // GET ALL - quizes/questions/answers
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

    // GET ID - quizes/questions/answers
    $app->get("/quiz/{id}", function(Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"],Quiz::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });
    $app->get("/question/{id}", function(Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"],Question::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });
    $app->get("/answer/{id}", function(Request $request, Response $response, array $args) {
        $results = resultFromId($args["id"],Answer::class);
        $response->getBody()->write($results["out"]);
        return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
    });
    // GET ACTIVE - quizes 
    $app->get("/quizes/active", function(Request $request, Response $response, array $args) {
        $actives = Quiz::where("active","=",3)->get()->toJson();
        if($actives === "[]"){
            $code = 404;
            $response->getBody()->write('{"message":"Empty result!"}');
        }
        else{
            $response->getBody()->write($actives);
            $code = 200;
        }
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    });

    // POST NEW - quizes/questions/answers
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
