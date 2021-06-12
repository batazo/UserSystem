<?php 
namespace UserSystem\Controllers\Score;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use \UserSystem\Components\Score;

class getScoreByName {
     public function get(Request $request, Response $response, $args) {
        header("Content-Type: application/json");

        $searchedScoreName = (!isset($args['searchname'])) ? 'Zero' : $args['searchname'];

        $data = Array(
            "Connection" => "Success",
            "UserName" => "UserName does not exist",
            "UserScore" => "UserScore does not exist"
        );
        
        $score = new Score();
        
        $scoreResultByUNAME = $score->getScoreByUserName($searchedScoreName);
        
        if(isset($scoreResultByUNAME[0]["UserName"])) {
            $data = Array(
                "Connection" => "Success",
                "UserName" => $scoreResultByUNAME[0]["UserName"],
                "UserScore" => $scoreResultByUNAME[0]["UserScore"]);
        }
        
        $templateVariables = [ 
            "data" => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
        return $renderer->render($response, "jsonView.php", $args);
     }
}