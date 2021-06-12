<?php

use Slim\Factory\AppFactory;

require_once "../private/vendor/autoload.php";

// Instantiate App
$app = AppFactory::create();


//Middlewares require
require_once 'Middlewares.php';

//Routes require
require_once 'Routes.php';

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();

//ErrorMiddleware
// Note: This middleware should be added last. It will not handle any exceptions/errors for middleware added after it.
$app->addErrorMiddleware(true, true, true);

$app->run();