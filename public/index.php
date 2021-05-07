<?php

use UserSystem\Components\DataSource;
use UserSystem\Components\Member;
use UserSystem\Components\Score;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once "../private/vendor/autoload.php";

$dataSource = new DataSource();
$member = new Member();
$score = new Score();

//dump($dataSource);
//dump($member);
//dump($score);


// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Welcome');
    return $response;
});



$app->run();

exit;
