<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;
//use Slim\Routing\RouteContext;

use UserSystem\Components\Member;
use UserSystem\Components\JWToken;

//Test before middleware
class authMiddleware 
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $data = Array(
            'Connection' => 'Success',
            'Access' => 'DENIED',
            'ActuallTimeStamp' => time(),
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
        );

        $authHeader = (isset($request->getHeader('Authorization')[0]))? $request->getHeader('Authorization')[0] : false;
        $userAuthenticationString = false;
        if($authHeader){
            $userAuthenticationString = filter_var(substr($authHeader, 7), FILTER_SANITIZE_STRING);
        }

        if(isset($request->getParsedBody()['jwtKEY'])){
            $userAuthenticationString = filter_var($request->getParsedBody()['jwtKEY'], FILTER_SANITIZE_STRING);
        }

        if($userAuthenticationString){
            $JWTokenInstance = new JWToken();
            $userdatasFromJWT = $JWTokenInstance->decodeToken($userAuthenticationString);
            if($userdatasFromJWT){
                if($userdatasFromJWT->iss === 'UserSystem'){       
                    $request = $request->withAttribute('Access', 'Allowed');
                    $request = $request->withAttribute('UserDatasFromJWT', $userdatasFromJWT);
                    return $handler->handle($request);
                }
            }    
        } 

            $response = new Response();
            $data = json_encode($data);
            $response->getBody()->write($data);
            $response = $response->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
            
            return $response;
    }
}