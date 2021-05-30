<?php
namespace UserSystem\Components;
$now = new \DateTimeImmutable();
$serverKeyInstance = new JWToken();

//$serverKey = $serverKeyInstance->getServerToken();
//echo $serverKey. '<br>';

$theUser = 'TEST_USER_NAME';

$userToken = 'PUT A TOKEN HERE';

//Get a token and print
//$userToken = $serverKeyInstance->createToken($theUser);
//echo $userToken. '<br>';

$tokenUser = $serverKeyInstance->decodeToken($userToken);
if($tokenUser){
echo $tokenUser->userName . '<br>'; 
echo $tokenUser->iss . ' -- EXP : ' . $tokenUser->exp . ' --- NOW : ' . $now->getTimestamp() .'<br>'; 
}

$tokenUserName = $serverKeyInstance->getTokenUserName($userToken);
echo $tokenUserName . '<br>'; 

echo $serverKeyInstance->tokenExpireCheck($userToken) . '<br>';
