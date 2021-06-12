<?php
namespace UserSystem\Controllers\Member;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use UserSystem\Components\Member;
use UserSystem\Components\JWToken;

class userprofileByJWT {
    public function get(Request $request, Response $response, $args) {
        header("Content-Type: application/json");
        $responseHeaderSet = 401;
        $JWTKey = false;
        $data = Array(
            'Connection' => 'Success',
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
        );

        $headers = getallheaders();

        if(isset($headers['Authorization'])){
            $JWTKey = filter_var(substr($headers['Authorization'], 7), FILTER_SANITIZE_STRING);
        }

        if(isset($_POST['jwtKEY'])){
            $JWTKey = filter_var($_POST['jwtKEY'], FILTER_SANITIZE_STRING);
        }

        if($JWTKey){

            $JWTokenInstance = new JWToken();
    
            $tokenUserDatas = $JWTokenInstance->decodeToken($JWTKey);
            if($tokenUserDatas){
                if($tokenUserDatas->iss === 'UserSystem'){
            
                    $now = new \DateTimeImmutable();
                    $memberInstance = new Member();
            
                    $memberProfile = $memberInstance->getMemberByUNAME($tokenUserDatas->userName);

                    if($memberProfile){
                        if(
                            $memberProfile[0]['UserName'] === $tokenUserDatas->userName && 
                            $memberProfile[0]['ID'] === $tokenUserDatas->userId && 
                            $memberProfile[0]['UserSecret'] === $tokenUserDatas->userSecret
                            ){
                            
                                $responseHeaderSet = 200;
                            
                                $data = Array(
                                    "Connection" => "Success",
                                    'CreatedTimeStamp' => $tokenUserDatas->iat,
                                    'ActuallTimeStamp' => $now->getTimestamp(),
                                    'ExpiredTimeStamp' => $tokenUserDatas->exp,
                                    'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
                                    'UserName' => $memberProfile[0]['UserName'],
                                    'UserAvatar' => $memberProfile[0]['UserAvatar'],
                                    'UserScore' => $memberProfile[0]['UserScore'],
                                    'UserSpeed' => $memberProfile[0]['UserSpeed'],
                                    'User' => 'Exist'
                                );
                        }
                    }
                }
            }
        }

        $templateVariables = [
            "data" => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
        return $renderer->render($response->withStatus($responseHeaderSet)->withHeader('X-UserProfile', 'REQ USER PROFILE'), "jsonView.php", $args);
    }
}