<?php 
namespace UserSystem\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;
//use Slim\Routing\RouteContext;

//Test before middleware
class testMiddleware 
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = new Response();
        if($request->getUri()->getPath() === '/tests/testroute/PASSW'){
            $userdatas = Array(
                'id' => 100,
                'UserName' => 'DummyUser',
                'UserAvatar' => null
            );
            $request = $request->withAttribute('Access', 'Allowed');
            $request = $request->withAttribute('UserDatas', $userdatas);
            return $handler->handle($request);
        } else {
            $data = Array(
                'Access' => 'DENIED',
                'UserDatas' => 'DENIED'
            );

            $data = json_encode($data);
            $response->getBody()->write($data);
            $response = $response->withHeader('Content-Type', 'application/json')
            ->withStatus(401);

            //$templateVariables = [
              //  'data' => $data
            //];

            //$renderer = new PhpRenderer('../private/src/Views',  $templateVariables);        
            //return $renderer->render($response->withStatus(401), "jsonView.php");
        }

        return $response;
    }
}