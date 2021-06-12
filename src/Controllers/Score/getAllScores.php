<?php 
namespace UserSystem\Controllers\Score;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use \UserSystem\Components\Score;

class getAllScores {
     public function get(Request $request, Response $response, $args) {

          header("Content-Type: application/json");

          $scoreAll = new Score();
          $scoreResult = $scoreAll->getAllUserScore();

          foreach ($scoreResult as &$userEntities) {
               $data[] = Array(
                    "Connection" => "Success",
                    "UserName" => $userEntities["UserName"],
                    "UserScore" => $userEntities["UserScore"]);
          }

          $templateVariables = [ 
	          "data" => $data
          ];

          $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
          return $renderer->render($response, "jsonView.php", $args);
     }
}