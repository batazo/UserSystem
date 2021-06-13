<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

//Test before middleware
class testMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);     
        $existingContent = (string) $response->getBody();
    
        $response = new Response();   

        
        if($existingContent === 'PASSW getT'){
            $response->getBody()->write('IGEN ' . $existingContent);
            $response->getBody()->write(' ÖN JOGOSULT! ');
               
        } else {
            $response = $response->withStatus(403);
            $response->getBody()->write('NEM JOGOSULT');
        }
        
        return $response->withHeader('X-TestCtrl', 'TestCtrl');
        //return $handler->handle($request);
    }
}
