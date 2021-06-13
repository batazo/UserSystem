<?php 
namespace UserSystem\Controllers\Tests;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;

class newTestController {
    public function get(Request $request, Response $response, $args) {
	    $name = $args['name'];
        
        $templateVariables = [
            'data' => $name
        ];
        
        $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
        
        return $renderer->render($response, "textView.php", $args);
    }

    public function getT(Request $request, Response $response, $args) {
	    $name = $args['name'];

        $data = Array(
            'Access' => $request->getAttribute('Access'),
            'UserDatas' => $request->getAttribute('UserDatas')
        );
        
        $templateVariables = [
            'data' => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
        
        return $renderer->render($response, "jsonView.php", $args);
    }
}

