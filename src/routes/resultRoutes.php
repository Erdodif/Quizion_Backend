<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $app->get("/results", function (Request $request, Response $response, array $args) {
        $result = Data::resultFromAll($args["id"], Results::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
};