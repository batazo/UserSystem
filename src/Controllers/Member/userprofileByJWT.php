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
        $requestedUserDatas = ($request->getAttribute('UserDatasFromJWT'))? $request->getAttribute('UserDatasFromJWT') : false;
        
        //dump($requestedUserDatas);
        
        $responseHeaderSet = 401;
        
        $data = Array(
            'ErrorMessage' => 'We are in controller $data',
            'Connection' => 'Success',
            'Access' => 'DENIED',
            'ActuallTimeStamp' => time(),
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
        );

        if($requestedUserDatas){
            $now = new \DateTimeImmutable();
            $memberInstance = new Member();
            $memberProfile = $memberInstance->getMemberByUNAME($requestedUserDatas->userName);

            if($memberProfile){
                if($memberProfile[0]['UserName'] === $requestedUserDatas->userName && 
                    $memberProfile[0]['ID'] === $requestedUserDatas->userId && 
                    $memberProfile[0]['UserSecret'] === $requestedUserDatas->userSecret){
                        $responseHeaderSet = 200;                            
                        $data = Array(
                                    "Connection" => "Success",
                                    'Access' => $request->getAttribute('Access'),
                                    'CreatedTimeStamp' => $requestedUserDatas->iat,
                                    'ActuallTimeStamp' => $now->getTimestamp(),
                                    'ExpiredTimeStamp' => $requestedUserDatas->exp,
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

        $templateVariables = [
            "data" => $data
        ];

        $renderer = new PhpRenderer('../private/src/Views', $templateVariables);
        return $renderer->render($response->withStatus($responseHeaderSet)->withHeader('X-UserProfile', 'REQ USER PROFILE'), "jsonView.php", $args);
    }
}