<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;

use \UserSystem\Controllers\Tests;
use \UserSystem\Controllers\Score;
use \UserSystem\Controllers\Member;
use \UserSystem\Controllers\Service;
use UserSystem\Middleware;

//Test ROUTES
//newRoutesTest with an own middleware
$app->get('/tests/testroute/{name}', Tests\newTestController::class . ':getT')->add(new Middleware\testMiddleware());

//Posttest
$app->post('/tests/posttest', function (Request $request, Response $response) : Response {
    $postparam = $request->getParsedBody();
	echo $postparam['test'];
	$response->getBody()->write('POSTTEST PATH');
    
	return $response;
});

//JWT token and server token test
$app->get('/tests/servertokentest', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/test');
    return $renderer->render($response, "token.php", $args);
});

//Statuscodes PHP header testrs
$app->get('/tests/statuscodes', function (Request $request, Response $response, $args) {
require_once '../private/src/Controllers/Tests/statuscodesController.php';
$renderer = new PhpRenderer('../private/test', $templateVariables);
return $renderer->render($response->withStatus($responseHeaderSet), "statuscodes.php", $args);
});

//JWT token and server token test
$app->get('/tests/dumper', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer('../private/test');
    return $renderer->render($response, "vardumper.php", $args);
});