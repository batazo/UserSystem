<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

//Test before middleware
class testMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);     
        $existingContent = (string) $response->getBody();

        
        if($existingContent === 'PASSW 2'){
        
        $response = new Response();
        $response->getBody()->write('IGEN ' . $existingContent);
        $response->getBody()->write(' ÖN JOGOSULT! ');
        
        return $response;
        
        }
        
        return $handler->handle($request);
    }
}