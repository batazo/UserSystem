<?php
namespace Usersystem;

use \Usersystem\Member;

require_once (__DIR__ . "/headerset.php");

require_once (__DIR__ . "/class/Member.php");

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
    

