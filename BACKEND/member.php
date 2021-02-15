<?php
namespace Usersystem;

use \Usersystem\Member;

if(isset($_SERVER["HTTP_REFERER"])){
$rest = substr($_SERVER["HTTP_REFERER"], 0, -1);
} else { $rest = "*";}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: ' . $rest . '');




   require_once (__DIR__ . "/class/Member.php");
   ini_set('session.cookie_domain', $rest);
   session_start();
   echo $_SESSION["UserName"];
   
   if (! empty($_SESSION["UserID"]) && ! empty($_SESSION["UserSecret"]) && ! empty($_SESSION["UserToken"]) ) {
	   $sessionUserID = $_SESSION['UserID'];
	   $sessionUserName = $_SESSION["UserName"];
	   $sessionUserSecret = $_SESSION["UserSecret"];
	   $sessionUserToken = $_SESSION["UserToken"];
	   
	   echo " * " . $sessionUserID . " - " . $sessionUserName . " - " . $sessionUserSecret . " - " . $sessionUserToken;
	   
   }