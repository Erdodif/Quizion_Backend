<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\Result;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $app->get("/results", function (Request $request, Response $response, array $args) {
        $result = Result::getAll($args["id"]);
        return $result->withResponse($response);
    });
};