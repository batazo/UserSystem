<?php
namespace Usersystem;
use \Usersystem\Score;

require_once __DIR__ . '/class/Score.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');


//Get Score By UserName
if(isset($_GET["userName"])){
	
	$getUser = $_GET["userName"];

    $score = new Score();
    $scoreResultByUNAME = $score->getScoreByUserName($getUser);

    if(isset($scoreResultByUNAME[0]["UserName"])) {
    $result = Array("UserName" => $scoreResultByUNAME[0]["UserName"],
    "UserScore" => $scoreResultByUNAME[0]["UserScore"]);
 
   echo json_encode($result, JSON_PRETTY_PRINT);
   } else {
	$result = json_decode('{"Username": "UserName does not exist", "UserScore":"UserScore does not exist"}');
	echo json_encode($result, JSON_PRETTY_PRINT); }

   exit;
 
}


/*  Get Score By User Id --  DISABLED
if(isset($_GET["userId"])){
    $score = new Score();
    $scoreResultByUID = $score->getScoreByUserId($_GET["userId"]);

    if(isset($scoreResultByUID[0]["ID"])) {
    $result = Array("UserName" => $scoreResultByUID[0]["UserName"],
     "UserScore" => $scoreResultByUID[0]["UserScore"]);
 
    echo json_encode($result, JSON_PRETTY_PRINT);
    }

exit;	
} 
*/


// Get all users scores If not a script $_GET parameter
$scoreAll = new Score();
$scoreResult = $scoreAll->getAllUserScore();

foreach ($scoreResult as &$userEntities) {
     $result[] = Array("UserName" => $userEntities["UserName"], "UserScore" => $userEntities["UserScore"]);
}

echo json_encode($result, JSON_PRETTY_PRINT);