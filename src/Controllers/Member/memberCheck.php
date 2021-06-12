<?php 
namespace UserSystem\Controllers\Member;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use UserSystem\Components\Member;

class memberCheck {
    public function get(Request $request, Response $response, $args) {
        $data;

        if(isset($args['name'])){
            if($args['name']){
                
                $memberName = trim($args['name']);

                $member = new Member();

                $checkMember = $member->checkMemberExist($memberName);
                $data = ($checkMember) ? "YES": "NO";    
            }
        }

        $templateVariables = [
            "data" => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
        return $renderer->render($response, "textView.php", $args);
    }

    public function getEmpty(Request $request, Response $response, $args) {
        $templateVariables = ["data" => 'Name is empty'];
        $renderer = new PhpRenderer('../private/src/Views',  $templateVariables);
        return $renderer->render($response, "textView.php", $args);
    }

}