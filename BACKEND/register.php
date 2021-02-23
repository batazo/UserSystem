<?php
namespace Usersystem;

use \Usersystem\Member;

require_once (__DIR__ . "/headerset.php");

require_once (__DIR__ . "/class/Member.php");

if(isset($_POST["reguser"]) && isset($_POST["regpwd"])){
    $regUser = $_POST["reguser"];
    $regPwd = $_POST["regpwd"];
    $member = new Member();
    $checkExistMember = $member->checkMemberExist($regUser);

    if($checkExistMember === "NO"){
            $registration = $member->registerUser($regUser, $regPwd);
            $data = Array(
                'UserExist' => "NO",
                'Registration' => $registration
            ); 
        } else {
            $data = Array(
                'UserExist' => "YES",
                'Registration' => "Failed"
            );
        };
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT);