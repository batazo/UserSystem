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

//All UserScore redirect
$app->redirect('/api/userscore/', '/api/userscore', 301);
//All UserScore
$app->get('/api/userscore', Score\getAllScores::class . ':get');
//User score by Name
$app->get('/api/userscore/{searchname}', Score\getScoreByName::class . ':get');

//Member exist check route
$app->group('/api/membercheck', function (RouteCollectorProxy $group) {
    $group->redirect('/', '', 301);
    $group->get('', Member\memberCheck::class . ':getEmpty');
    $group->get('/{name}', Member\memberCheck::class . ':get');
});

//User profile JSON DATAs ( by JWT )
$app->map(['GET', 'POST'], '/api/user', Member\userprofileByJWT::class . ':get')->add(new Middleware\authMiddleware());

// Allow preflight requests for JWT user
$app->options('/api/user', function ($request, $response, $args) {
    return $response;
});

//Redirect to /api/user
$app->redirect('/api/user/', '/api/user', 301);

//User profile JSON DATAs ( by sessionid )
$app->map(['GET', 'POST'], '/api/userprofile', Member\userprofileBySES::class . ':get');

// Allow preflight requests for session user
$app->options('/api/userprofile', function ($request, $response, $args) {
    return $response;
});

//Redirect to /apui/userprofile
$app->redirect('/api/userprofile/', '/api/userprofile', 301);

//User profile LOCAL JSONdata in same domain, if backeds and frontend are on same domain and PHPSESSID cookie is exist
$app->get('/api/profile', Member\userprofileOnLocal::class . ':get');

//Login endpoint route
$app->redirect('/api/login/', '/api/login', 301);
$app->post('/api/login', Service\loginUser::class . ':get');

//Register endpoint route
$app->redirect('/api/register/', '/api/register', 301);
$app->post('/api/register', Service\registerUser::class . ':get');