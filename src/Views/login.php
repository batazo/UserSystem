<?php
namespace UserSystem\Components;

use UserSystem\Components\Member;

require_once "../private/vendor/autoload.php";

//require_once (__DIR__ . "/headerset.php");


if(isset($_POST['nameField']) && isset($_POST['passField'])){

	session_start();
	
	$username = filter_var($_POST["nameField"], FILTER_SANITIZE_STRING);	
    $password = filter_var($_POST["passField"], FILTER_SANITIZE_STRING);
	
    //require_once (__DIR__ . "/class/Member.php");
	
	$member = new Member();
    $isLoggedIn = $member->processLogin($username, $password);
	
    header("Content-Type: application/json");
	
	 if (! $isLoggedIn) {
		 
		 /* $data = Array(
          'Login' => 'Failed',
		  'UserID' => 'Failed',
		  'UserName' => 'Failed',
		  'UserSecret' => 'Failed',
		  'UserToken' => 'Failed',
		 );
        */
		$data = '{"Login": "Failed", "UserID":"Failed", "UserName":"Failed", "UserRegistredAt":"Failed", "UserSecret":"Failed", "UserToken":"Failed"}';
		
    } else {
	    $memberProfile = $member->getMemberByUNAME($username);
		
		$data = '{"Login": "Success", "SessionId":"'. session_id() .'" ,"UserID":"'. $_SESSION['UserID'] .'","UserName":"'. $_SESSION['UserName'] .'", "UserRegistredAt":"'. $memberProfile[0]['UserRegTime'] .'", "UserSecret":"'. $memberProfile[0]['UserSecret'] .'", "UserToken":"'. $memberProfile[0]['UserToken'] .'"}';
	}
	
	$data = json_encode(json_decode($data), JSON_PRETTY_PRINT);
	echo $data;

};
