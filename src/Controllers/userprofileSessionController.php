<?php         
use UserSystem\Components\Member;

header("Content-Type: application/json");
$responseHeaderSet = 401;

$data = Array(
    "Connection" => "Success",
    'UserName' => 'Failed',
    'User' => 'DoesnotExist'
 );

 if(isset($_POST['sessid'])){

    session_id($_POST['sessid']);
    session_start();

    if(isset($_SESSION["UserName"]) || isset($_SESSION["UserID"])){
        $member = new Member();
        $now = new \DateTimeImmutable();
        $memberProfile = $member->getMemberByUNAME($_SESSION["UserName"]);
        $memberScore = (isset($memberProfile[0]["UserScore"])) ? $memberProfile[0]["UserScore"] : "UserScore unavailable";
     
        $responseHeaderSet = 200;
        $data = Array(
            "Connection" => "Success",
            'ActuallTimeStamp' => $now->getTimestamp(),
            'CreatedTimeStamp' => $_SESSION["CreatedTimeStamp"],
            'ExpiredTimeStamp' => $_SESSION["CreatedTimeStamp"] + 2592000,
            'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
            'UserName' => $_SESSION["UserName"],
            'UserAvatar' => $memberProfile[0]['UserAvatar'],
            'UserScore' => $memberScore,
            'UserSpeed' => $memberProfile[0]['UserSpeed'],
            'User' => 'Exist'
        );    
   }
}

$templateVariables = [
    "data" => $data
];