<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

//Test before middleware
class testMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);     
        $existingContent = (string) $response->getBody();

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();     
        
        $testR = ($existingContent === 'PASSW 2')? 'IGEN ÖN JOGOSULT':'NEM JOGOSULT';
        
        $headers = getallheaders();
        //dump($headers);
        $name = $route->getArgument('name');
        echo $name;   
        $response = new Response();
        $response->getBody()->write($testR . ' ' . $existingContent);
        $response->getBody()->write(' ' . $testR);
        
        return $response;
    }
}