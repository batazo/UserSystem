<?php
namespace UserSystem\Components;

//require_once (__DIR__ . "/headerset.php");


if(isset($_POST['nameField']) && isset($_POST['passField'])){

	session_start();
	
	$username = filter_var($_POST["nameField"], FILTER_SANITIZE_STRING);	
    $password = filter_var($_POST["passField"], FILTER_SANITIZE_STRING);
	
    //require_once (__DIR__ . "/class/Member.php");
	
	$member = new Member();
	$JWTok = new JWToken();
    $isLoggedIn = $member->processLogin($username, $password);

	 if (! $isLoggedIn) {
		 
		 /* $data = Array(
          'Login' => 'Failed',
		  'UTOK' => 'Failed',
		  'UserID' => 'Failed',
		  'UserName' => 'Failed',
		  'UserSecret' => 'Failed',
		  'UserToken' => 'Failed',
		 );
        */
		$data = '{"Login": "Failed", "UTOK": "Failed", "UserID":"Failed", "UserName":"Failed", "UserRegistredAt":"Failed", "UserSecret":"Failed", "UserToken":"Failed"}';
		
    } else {
	    $memberProfile = $member->getMemberByUNAME($username);
		//JWT Token Create
		$createdToken = $JWTok->createToken($memberProfile[0]["UserName"], $memberProfile[0]["ID"], $memberProfile[0]["UserSecret"], 133920);

		$data = '{"Login": "Success", "UTOK": "' . $createdToken . '", "SessionId":"'. session_id() .'" ,"UserID":"'. $_SESSION['UserID'] .'","UserName":"'. $_SESSION['UserName'] .'", "UserRegistredAt":"'. $memberProfile[0]['UserRegTime'] .'", "UserSecret":"'. $memberProfile[0]['UserSecret'] .'", "UserToken":"'. $memberProfile[0]['UserToken'] .'"}';
	}
	
	header("Content-Type: application/json");
	$data = json_encode(json_decode($data), JSON_PRETTY_PRINT);
	echo $data;

};
