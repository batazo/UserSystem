<?php

header('Access-Control-Allow-Origin: *');

if(isset($_POST['nameField']) && isset($_POST['passField'])){
	echo "Hello : " . $_POST['nameField'] . " - Your PW is : " . $_POST['passField'];
	
};
