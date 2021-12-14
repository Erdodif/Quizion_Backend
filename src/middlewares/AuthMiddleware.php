<?php

namespace Quizion\Backend\Middlewares;

use \Error;
use Quizion\Backend\Models\Token;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Quizion\Backend\Companion\Message;
use Quizion\Backend\Companion\Data;
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
                $data = new Data(
                    ERROR_BAD_REQUEST,
                    new Message("Invalid request header!")
                );
            } else {
                $authArray = mb_split(" ", $auth[0]);
                if ($authArray[0] !== 'Bearer') {
                    $data = new Data(
                        ERROR_METHOD_NOT_ALLOWED,
                        new Message("Unsupported method!")
                    );
                } else {
                    $tokenStr = $authArray[1];
                    if ($tokenStr === "") {
                        $data = new Data(
                            ERROR_UNAUTHORIZED,
                            new Message("Login reqired!")
                        );
                    } else {
                        $token = Token::getTokenByKey($tokenStr);
                        if(!$token){
                            throw new Error("No result in database...");
                        }
                        try {
                            $request = $request->withAttribute("userID",$token->user_id);
                            $out = $handler->handle($request);
                            return $out;
                        } catch (Error $e) {
                            $data = new Data(
                                ERROR_INTERNAL,
                                new Message("An internal error occured! " . $e)
                            );
                            //TODO #15 Production-be kiszedni a hibakódot!
                        }
                    }
                }
            }
        } catch (Error $e) {
            $data = new Data(
                ERROR_UNAUTHORIZED,
                new Message("Invalid or expired Token!")
            );
        }
        $response = $this->responseFactory->createResponse();
        return $data->withResponse($response);
    }
}
