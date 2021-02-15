<?php
namespace Usersystem;

use \Usersystem\Member;

if(isset($_SERVER["HTTP_REFERER"])){
$rest = substr($_SERVER["HTTP_REFERER"], 0, -1);
} else { $rest = "*";}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: ' . $rest . '');


if(isset($_POST['nameField']) && isset($_POST['passField'])){
	ini_set('session.cookie_domain', $rest);
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
