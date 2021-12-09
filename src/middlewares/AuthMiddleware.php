<?php

namespace Quizion\Backend\Middlewares;

use Exception;
use Quizion\Backend\Models\Token;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Quizion\Backend\Companion\Message;
use Slim\App;

class AuthMiddleware
{

    private $responseFactory;

    public function __construct($responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $auth = $request->getHeader("Authorization");
        try {
            if (count($auth) !== 1) {
                $code = ERROR_BAD_REQUEST;
                $out = new Message("Invalid request header!");
            } else {
                $authArray = mb_split(" ", $auth[0]);
                if ($authArray[0] !== 'Bearer') {
                    $code = ERROR_METHOD_NOT_ALLOWED;
                    $out = new Message("Unsupported method!");
                } else {
                    $tokenStr = $authArray[1];
                    if ($tokenStr === "") {
                        $code = ERROR_UNAUTHORIZED;
                        $out = new Message("Login reqired!");
                    } else {
                        Token::where("token", $tokenStr)->firstOrFail();
                        $out = $handler->handle($request);
                        return $out;
                    }
                }
            }
        } catch (Exception $e) {
            $code = ERROR_UNAUTHORIZED;
            $out = new Message("Invalid or expired Token!");
        }
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($out->toJson());
        return $response->withHeader("Content-Type", "application/json")->withStatus($code);
    }
}
