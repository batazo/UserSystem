<?php
namespace UserSystem\Components;

use UserSystem\Components\Member;

require_once "../private/vendor/autoload.php";

//require_once (__DIR__ . "/headerset.php");

if(isset($_POST["reguser"]) && isset($_POST["regpwd"])){
    $regUser = trim($_POST["reguser"]);
    $regPwd = $_POST["regpwd"];
    $member = new Member();
    $existMember = $member->checkMemberExist($regUser);

    if(!$existMember){
            $registration = $member->registerUser($regUser, $regPwd);
			$successCheck = ($registration) ? "Success" : "Failed";
            $data = Array(
                'UserExist' => "NO",
                'Registration' => $successCheck
            ); 
        } else {
            $data = Array(
                'UserExist' => "YES",
                'Registration' => "Failed"
            );
        };

    echo json_encode($data, JSON_PRETTY_PRINT);
}
    

