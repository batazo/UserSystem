<?php
namespace UserSystem\Components;

//CHECK MEMBER
//$member_name variable form router APP!
if(isset($member_name)){
    if($member_name){
            $member = new Member();
            $memberName = trim($member_name);
            $checkMember = $member->checkMemberExist($memberName);
			$checkMember = ($checkMember) ? "YES": "NO";
        echo $checkMember;
    }
}

//USER PROFILE BY SESSION ID
//$getUserProfile variable form router APP!
if(isset($getUserProfile)){
if($getUserProfile){
     if(isset($_POST['sessid'])){

        session_id($_POST['sessid']);
        session_start();

        header("Content-Type: application/json");

        if(isset($_SESSION["UserName"]) || isset($_SESSION["UserID"])){
            $member = new Member();
            $memberProfile = $member->getMemberByUNAME($_SESSION["UserName"]);
            $memberScore = (isset($memberProfile[0]["UserScore"])) ? $memberProfile[0]["UserScore"] : "UserScore unavailable";
         
         $data = Array(
             'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
             'UserName' => $_SESSION["UserName"],
             'UserAvatar' => $memberProfile[0]['UserAvatar'],
             'UserScore' => $memberScore,
             'UserSpeed' => $memberProfile[0]['UserSpeed'],
             'User' => 'Exist'
         );
            echo json_encode($data, JSON_PRETTY_PRINT);
       } else {
         $data = Array(
            'UserName' => 'Failed',
            'User' => 'DoesnotExist'
         );
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode($data, JSON_PRETTY_PRINT);
       }

    }
}
}



//USERPROFILE LOCAL
//$getUserProfileLocal variable form router APP!
if(isset($getUserProfileLocal)){
if($getUserProfileLocal){

        session_start();

        header("Content-Type: application/json");

        if(isset($_SESSION["UserName"]) || isset($_SESSION["UserID"])){
            $member = new Member();
            $memberProfile = $member->getMemberByUNAME($_SESSION["UserName"]);
            $memberScore = (isset($memberProfile[0]["UserScore"])) ? $memberProfile[0]["UserScore"] : "UserScore unavailable";
         
         $data = Array(
            'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
            'UserName' => $_SESSION["UserName"],
			'UserRegistredAt' => $memberProfile[0]["UserRegTime"],
            'UserAvatar' => $memberProfile[0]['UserAvatar'],
            'UserScore' => $memberScore,
            'UserSpeed' => $memberProfile[0]['UserSpeed'],
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