<?php

use UserSystem\Components\DataSource;
use UserSystem\Components\Member;
use UserSystem\Components\Score;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require_once "../private/vendor/autoload.php";

$dataSource = new DataSource();
$member = new Member();
$score = new Score();

//dump($dataSource);
//dump($member);
//dump($score);

// Instantiate App
$app = AppFactory::create();

// This middleware will append the response header Access-Control-Allow-Methods with all allowed methods
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);
	
		if(isset($_SERVER["HTTP_REFERER"])){
    $restprefix = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    
    $rest = $restprefix . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } else {
         $rest = "*";
    }

    $response = $response->withHeader('Access-Control-Allow-Origin', $rest);
	$response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    // Optional: Allow Ajax CORS requests with Authorization header
    // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();


// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Welcome');
    return $response;
});


$app->post('/api/posttest', function (Request $request, Response $response) : Response {
    $postparam = $request->getParsedBody();
	echo $postparam['test'];
	$response->getBody()->write('POSTTEST PATH');
    
	return $response;
});


$app->any('/api/login', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/src/Views');
    return $renderer->render($response, "login.php", $args);
});



$app->run();