<?php 
namespace UserSystem\Controllers\Service;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use UserSystem\Components\Member;
use UserSystem\Components\JWToken;

class loginUser {
    public function get(Request $request, Response $response, $args) {

        header("Content-Type: application/json");

        $responseHeaderSet = 401;

        $data = Array(
            "Connection" => "Success",
            'Login' => 'Failed',
            'SessionId' => 'Failed',
            'UTOK' => 'Failed',
            'UserName' => 'Failed',
            'UserScore' => 'Failed'
        );

        if(isset($_POST['nameField']) && isset($_POST['passField'])){
	        session_start();
            $username = filter_var($_POST["nameField"], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST["passField"], FILTER_SANITIZE_STRING);
            $member = new Member();
            $JWTok = new JWToken();
            $isLoggedIn = $member->processLogin($username, $password);
            
                if ($isLoggedIn){
                    $memberProfile = $member->getMemberByUNAME($username);
                    //JWT Token Create
                    $createdToken = $JWTok->createToken($memberProfile[0]["UserName"],
                                                        $memberProfile[0]["ID"],
                                                        $memberProfile[0]["UserSecret"],
                                                        44640);
                    $responseHeaderSet = 200;
                    header("Authorization: Bearer $createdToken");
                    $data = Array(
                                "Connection" => "Success",
                                'Login' => 'Success',
                                'SessionId' => session_id(),
                                'UTOK' => $createdToken,
                                'UserName' => $_SESSION['UserName'],
                                'UserScore' => $memberProfile[0]['UserScore']                   
                    );
        
                }       
        }

        $templateVariables = [
            "data" => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
        return $renderer->render($response->withStatus($responseHeaderSet), "jsonView.php", $args);
    }
}