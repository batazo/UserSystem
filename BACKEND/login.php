<?php
namespace Usersystem;

use \Usersystem\Member;

require_once (__DIR__ . "/headerset.php");


if(isset($_POST['nameField']) && isset($_POST['passField'])){

	session_start();
	
	$username = filter_var($_POST["nameField"], FILTER_SANITIZE_STRING);	
    $password = filter_var($_POST["passField"], FILTER_SANITIZE_STRING);
	
    require_once (__DIR__ . "/class/Member.php");
	
	$member = new Member();
    $isLoggedIn = $member->processLogin($username, $password);
	
	 if (! $isLoggedIn) {
        header("Content-Type: application/json");
		echo json_encode('{"Login": "Failed", "UserID":"Failed","UserName":"Failed", "UserSecret":"Failed", "UserToken":"Failed"}');
    } else {
	    $memberProfile = $member->getMemberByUNAME($username);
		header("Content-Type: application/json");
		echo json_encode('{"Login": "Success", "SessionId":"'. session_id() .'" ,"UserID":"'. $_SESSION['UserID'] .'","UserName":"'. $_SESSION['UserName'] .'", "UserSecret":"'. $memberProfile[0]['UserSecret'] .'", "UserToken":"'. $memberProfile[0]['UserToken'] .'"}');
	}

};
