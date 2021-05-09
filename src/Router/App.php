<?php

use UserSystem\Components\DataSource;
use UserSystem\Components\Member;
use UserSystem\Components\Score;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;
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


// Add routes
//Main route
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Welcome');
    return $response;
});

//Test ROUTE
$app->post('/api/posttest', function (Request $request, Response $response) : Response {
    $postparam = $request->getParsedBody();
	echo $postparam['test'];
	$response->getBody()->write('POSTTEST PATH');
    
	return $response;
});

//User score searcher route
$app->get('/api/userscore/{searchname}', function (Request $request, Response $response, $args) {
	$scorename = (!isset($args['searchname'])) ? 'Zero' : $args['searchname'];
	$templateVar = [
		"ScoreByNameSwitcher" => true,
		"nameForScore" => $scorename
    ];
    $renderer = new PhpRenderer('../private/src/Views', $templateVar);
    return $renderer->render($response, "userscore.php", $args);
});

//All user score from system
$app->get('/api/userscore', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/src/Views');
    return $renderer->render($response, "userscore.php", $args);
});

//Member exist check route
$app->get('/api/membercheck/{name}', function (Request $request, Response $response, $args) {
	$searched_name = $args['name'];
	$templateVariables = [
      "member_name" => $searched_name
    ];
	
    $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
    return $renderer->render($response, "member.php", $args);
});

//User profile JSON DATAs
$app->post('/api/userprofile', function (Request $request, Response $response, $args) {
	$templateVariables = [
      "getUserProfile" => true
    ];
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response, "member.php", $args);
});

//User profile JSONdata in same domain, if backeds and frontend are on same domain and PHPSESSID cookie is exist
$app->get('/api/profile', function (Request $request, Response $response, $args) {
	$templateVariables = [
      "getUserProfileLocal" => true
    ];
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response, "member.php", $args);
});

//Login endpoint route
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/src/Views');
    return $renderer->render($response, "login.php", $args);
});

//Register endpoint route
$app->post('/api/register', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/src/Views');
    return $renderer->render($response, "register.php", $args);
});

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();

//ErrorMiddleware
// Note: This middleware should be added last. It will not handle any exceptions/errors for middleware added after it.
$app->addErrorMiddleware(true, true, true);

$app->run();