<?php
namespace Quizion\Backend\Middlewares;

use Exception;
use Quizion\Backend\Models\Token;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Quizion\Backend\Companion\Message;
use Slim\App;

class AuthMiddleware{

    private $responseFactory;

    public function __construct($responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, RequestHandler $handler) :Response
    {
        $auth = $request->getHeader("Authorization");
        try{
            if(count($auth) !==1){
                throw new Exception("Hibás a kérés feljéce!");
            }
            $authArray = mb_split(" ", $auth[0]);
            if($authArray[0]!== 'Bearer'){
                throw new Exception("Nem támogatott autentikaciós módszer!");
            }
            $tokenStr = $authArray[1];
            Token::where("token", $tokenStr)->firstOrFail();
            $response = $handler->handle($request);
        }
        catch(Exception $e){
            $response = $this->responseFactory->createResponse();
            $response->withStatus(ERROR_UNAUTHORIZED);
            $response->getBody()->write((new Message("Invalid or expired Token!"))->toJson());
        }
        return $response;
    }
}