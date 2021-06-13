<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

//Test before middleware
class testMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);     
        $existingContent = (string) $response->getBody();
        
        $testR = ($existingContent === 'PASSW 2')? 'IGEN ÖN JOGOSULT':'NEM JOGOSULT';
        
        $headers = getallheaders();
        dump($headers);

        $response = new Response();
        $response->getBody()->write($testR . ' ' . $existingContent);
        $response->getBody()->write(' ' . $testR);
        
        return $response;
    }
}