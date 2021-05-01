<?php
namespace Usersystem;

use \Usersystem\Member;

require_once (__DIR__ . "/headerset.php");

require_once (__DIR__ . "/class/Member.php");

if(isset($_GET['memberCheck'])){
    if($_POST['checkeMember']){
            $member = new Member();
            $memberName = trim($_POST['checkeMember']);
            $checkMember = $member->checkMemberExist($memberName);
			$checkMember = ($checkMember) ? "YES": "NO";
        echo $checkMember;
    }
}


if(isset($_GET['profile'])){
     if(isset($_POST['sessid'])){

        session_id($_POST['sessid']);
        session_start();

        header("Content-Type: application/json");

        if(isset($_SESSION["UserName"]) || isset($_SESSION["UserID"])){
            $member = new Member();
            $memberProfile = $member->getMemberByUNAME($_SESSION["UserName"]);
            $memberScore = (isset($memberProfile[0]["UserScore"])) ? $memberProfile[0]["UserScore"] : "UserScore unavailable";
         
         $data = Array(
             'UserName' => $_SESSION["UserName"],
			 'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
             'UserSecret' => $_SESSION["UserSecret"],
             'UserToken' => $_SESSION["UserToken"],
             'UserScore' => $memberScore,
             'User' => 'Exist'
         );
            echo json_encode($data, JSON_PRETTY_PRINT);
       } else {
         $data = Array(
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
         );
           echo json_encode($data, JSON_PRETTY_PRINT);
       }

    }
}

if(isset($_GET['profile-local'])){

        session_start();

        header("Content-Type: application/json");

        if(isset($_SESSION["UserName"]) || isset($_SESSION["UserID"])){
            $member = new Member();
            $memberProfile = $member->getMemberByUNAME($_SESSION["UserName"]);
            $memberScore = (isset($memberProfile[0]["UserScore"])) ? $memberProfile[0]["UserScore"] : "UserScore unavailable";
         
         $data = Array(
             'UserName' => $_SESSION["UserName"],
			 'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
             'UserSecret' => $_SESSION["UserSecret"],
             'UserToken' => $_SESSION["UserToken"],
             'UserScore' => $memberScore,
             'User' => 'Exist'
         );
            echo json_encode($data, JSON_PRETTY_PRINT);
       } else {
         $data = Array(
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
         );
           echo json_encode($data, JSON_PRETTY_PRINT);
       }

}