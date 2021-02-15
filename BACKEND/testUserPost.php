<?php

if(isset($_SERVER["HTTP_REFERER"])){
$restprefix = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";

$rest = $restprefix . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
} else { $rest = "*";}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: ' . $rest . '');

if(isset($_POST['nameField']) && isset($_POST['passField'])){
	echo "Hello : " . $_POST['nameField'] . " - Your PW is : " . $_POST['passField'];
	
};
