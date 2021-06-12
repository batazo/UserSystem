<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

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
	$rest = "null";
	}

    $response = $response->withHeader('Access-Control-Allow-Origin', $rest);
	$response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    //$response = $response->withHeader('Access-Control-Allow-Headers', 'Authorization');
    $response = $response->withHeader('Access-Control-Expose-Headers', 'Authorization');
    $response = $response->withHeader('Access-Control-Request-Headers', 'Authorization');

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        $response = $response->withHeader('X-OPTION-CHECKER-2', 'YES, It is option');
    } else {

        $response = $response->withHeader('X-OPTION-CHECKER-2', 'NO, It is not an option request');
    }
    
    if ( ! $request->isOptions()) {
        // this continues the normal flow of the app, and will return the proper body
        $response = $response->withHeader('X-OPTION-CHECKER', 'NO. It is not isOptions()');
        return $response;
    } else {
        //stops the app, and sends the response
        $response = $response->withHeader('X-OPTION-CHECKER', 'YES. It is isOptions()');
        return $response;
    }

    
});