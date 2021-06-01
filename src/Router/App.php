<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require_once "../private/vendor/autoload.php";

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

//Endpoints table of contents
$app->get('/api/', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/src/Views');
    return $renderer->render($response, "api.php", $args);
});
//Redirect API to API/
$app->redirect('/api', '/api/', 301);

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
//Redirect /api/userscore/ to /api/userscore
$app->redirect('/api/userscore/', '/api/userscore', 301);

//Member exist check route
$app->get('/api/membercheck/{name}', function (Request $request, Response $response, $args) {
	$searched_name = ($args['name']) ? $args['name'] : 'ZERO';
    require_once '../private/src/Controllers/memberCheckController.php';
    $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
    return $renderer->render($response, "textView.php", $args);
});
$app->get('/api/membercheck/', function (Request $request, Response $response, $args) {
	
    $templateVariables = ["data" => 'Name is empty'];

    $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
    return $renderer->render($response, "textView.php", $args);
});
$app->redirect('/api/membercheck', '/api/membercheck/', 301);

//User profile JSON DATAs ( by sessionid )
$app->map(['GET', 'POST'], '/api/userprofile', function (Request $request, Response $response, $args) {

    require_once '../private/src/Controllers/userprofileSessionController.php';

    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
});

//User profile JSON DATAs ( by JWT )
$app->map(['GET', 'POST'], '/api/user', function (Request $request, Response $response, $args) {

    require_once '../private/src/Controllers/userprofileJWTController.php';
    
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
});

//Redirect /api/user/ to /api/user
$app->redirect('/api/user/', '/api/user', 301);

//User profile LOCAL JSONdata in same domain, if backeds and frontend are on same domain and PHPSESSID cookie is exist
$app->get('/api/profile', function (Request $request, Response $response, $args) {
    require_once '../private/src/Controllers/userprofileLocalController.php';
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
});

//Login endpoint route
$app->post('/api/login', function (Request $request, Response $response, $args) {
    require_once '../private/src/Controllers/loginController.php';
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
});
$app->redirect('/api/login/', '/api/login', 301);

//Register endpoint route
$app->post('/api/register', function (Request $request, Response $response, $args) {
    require_once '../private/src/Controllers/registerController.php';
    $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
    return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
});


//Test ROUTES
//Posttest
$app->post('/api/posttest', function (Request $request, Response $response) : Response {
    $postparam = $request->getParsedBody();
	echo $postparam['test'];
	$response->getBody()->write('POSTTEST PATH');
    
	return $response;
});

//JWT token and server token test
$app->get('/api/servertokentest', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/test');
    return $renderer->render($response, "token.php", $args);
});

//Statuscodes PHP header testrs
$app->get('/api/statuscodes', function (Request $request, Response $response, $args) {

require_once '../private/src/Controllers/statuscodesController.php';
    
$renderer = new PhpRenderer('../private/test', $templateVariables);

return $renderer->render($response->withStatus($responseHeaderSet), "statuscodes.php", $args);
});

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();

//ErrorMiddleware
// Note: This middleware should be added last. It will not handle any exceptions/errors for middleware added after it.
$app->addErrorMiddleware(true, true, true);

$app->run();