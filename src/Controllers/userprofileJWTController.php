<?php
use UserSystem\Components\Member;
use UserSystem\Components\JWToken;

header("Content-Type: application/json");
$responseHeaderSet = 401;

$data = Array(
    'UserName' => 'Failed',
    'User' => 'DoesnotExist'
 );

if(isset($_POST['jwtKEY'])){

    $JWTokenInstance = new JWToken();
    
    $tokenUserDatas = $JWTokenInstance->decodeToken($_POST['jwtKEY']);
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