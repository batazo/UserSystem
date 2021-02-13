<?php
namespace Usersystem;

use \Usersystem\Member;


header('Access-Control-Allow-Origin: *');

if(isset($_POST['nameField']) && isset($_POST['passField'])){
	session_start();
	$username = filter_var($_POST["nameField"], FILTER_SANITIZE_STRING);
	
    $password = filter_var($_POST["passField"], FILTER_SANITIZE_STRING);
	


    require_once (__DIR__ . "/class/Member.php");
	
	$member = new Member();
    $isLoggedIn = $member->processLogin($username, $password);
	
	
	 if (! $isLoggedIn) {
        header("Content-Type: application/json");
		echo json_encode("{'Login': 'Failed'}");
    } else {
		header("Content-Type: application/json");
		echo json_encode("{'Login': 'Success'}");
	}
	
	
};
