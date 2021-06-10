<?php 
use UserSystem\Components\Member;
header("Content-Type: application/json");

$responseHeaderSet = 409;
$successCheck;
$existMemberCheck;
 
$data = Array(
    'Connection' => 'Success',
    'UserExisted' => 'NotChecked',
    'Registration' => 'NotChecked'
);

if(isset($_POST["reguser"]) && isset($_POST["regpwd"])){
    
    $regUser = trim($_POST["reguser"]);
    $regPwd = $_POST["regpwd"];

    $member = new Member();
    $existMember = $member->checkMemberExist($regUser);
    $existMemberCheck = 'YES';
    $successCheck = 'Failed';

    if(!$existMember){
        $existMemberCheck = 'NO';
        $registration = $member->registerUser($regUser, $regPwd);
		$successCheck = ($registration) ? "Success" : "Failed";
        $responseHeaderSet = ($registration) ? 201 : 409;
    }
    $data = Array(
        "Connection" => "Success",
        'UserExisted' => $existMemberCheck,
        'Registration' => $successCheck
    );
}

$templateVariables = [
    "data" => $data
];