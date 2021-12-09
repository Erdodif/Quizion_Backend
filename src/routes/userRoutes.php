<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quizion\Backend\Companion\Data;
use Quizion\Backend\Models\User;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Models\Token;
use Slim\Routing\RouteCollectorProxy;

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $app->get("/users", function (Request $request, Response $response) {
        $result = Data::resultFromAll(User::class);
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
    $app->group("/user", function (RouteCollectorProxy $group) {
        $group->post("", function (Request $request, Response $response) {
            try {
                $input = json_decode($request->getBody(), true);
                $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                $user = User::create($input);
                $user->save();
                $code = RESPONSE_CREATED;
            } catch (Error $e) {
                $user = new Message($e);
                $code = ERROR_INTERNAL;
            }
            $response->getBody()->write($user->toJson());
            return $response->withHeader("Content-Type", "application/json")->withStatus($code);
        });

        $group->group("/{identifier}", function (RouteCollectorProxy $group) {
            $group->get("", function (Request $request, Response $response, array $args) {
                $identifier = $args["identifier"];
                if (is_numeric($identifier)) {
                    $results = Data::resultFromId($args["identifier"], User::class);
                } else if (str_contains($identifier, "@")) {
                    $results = Data::getUserByEmail($identifier);
                } else {
                    $results = Data::getUserByName($identifier);
                }
                $response->getBody()->write($results["out"]->toJson());
                return $response->withHeader("Content-Type", "application/json")->withStatus($results["code"]);
            });

            $group->put("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], User::class);
                if ($result["code"] == RESPONSE_OK) {
                    $input = json_decode($request->getBody(), true);
                    if (isset($input["password"])) {
                        $input["password"] = password_hash($input["password"], PASSWORD_ARGON2I);
                    }
                    $result["out"]->fill($input);
                    $result["out"]->save();
                }
                $response->getBody()->write($result["out"]->toJson());
                return $response->withStatus($result["code"]);
            });

            $group->delete("", function (Request $request, Response $response, array $args) {
                $result = Data::resultFromId($args["id"], User::class);
                if ($result["code"] == RESPONSE_OK) {
                    $result["out"]->delete();
                    $result["code"] = RESPONSE_NO_CONTENT;
                } else {
                    $result["code"] = ERROR_NOT_FOUND;
                    $response->getBody()->write($result["out"]->toJson());
                }
                return $response->withStatus($result["code"]);
            });
        });
    });
    $app->post("/login", function (Request $request, Response $response) {
        $input = json_decode($request->getBody(), true);
        $identifier = $input["identifier"];
        $password = $input["password"];
        $result = Data::getUserByAny($identifier);
        try {
            if ($result["code"] == RESPONSE_OK && password_verify($password, $result["out"]->password)) {
                $stillNeeded = true;
                while ($stillNeeded) {
                    try {
                        $key = Data::createKey();
                        $token = Token::create(array("user_id"=>$result["out"]->id,"token"=>$key));
                        $token->save();
                        $stillNeeded = false;
                    } catch (Error $e) {
                    }
                }
                $result["code"] = RESPONSE_CREATED;
                $token->makeHidden(["user_id"]);
                $token->makeVisible(["token"]);
                $result["out"] = $token;
            } else {
                throw new Error("Invalid userID or password!");
            }
        } catch (Error $e) {
            $result["code"] = ERROR_BAD_REQUEST;
            $result["out"] = new Message($e->getMessage());
        }
        $response->getBody()->write($result["out"]->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($result["code"]);
    });
};
