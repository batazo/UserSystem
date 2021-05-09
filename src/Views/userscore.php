<?php
namespace Usersystem\Components;

use \Usersystem\Components\Score;

require_once "../private/vendor/autoload.php";
//require_once (__DIR__ . "/headerset.php");

//Get Score By UserName
if(isset($nameForScore) && isset($ScoreByNameSwitcher)){
	if($ScoreByNameSwitcher && $nameForScore){
	$getUser = $nameForScore;
    $score = new Score();
    $scoreResultByUNAME = $score->getScoreByUserName($getUser);

    if(isset($scoreResultByUNAME[0]["UserName"])) {
		$result = Array("UserName" => $scoreResultByUNAME[0]["UserName"],
		"UserScore" => $scoreResultByUNAME[0]["UserScore"]);
		header("Content-Type: application/json");
		echo json_encode($result, JSON_PRETTY_PRINT);
	} else {
		$result = json_decode('{"UserName": "UserName does not exist", "UserScore":"UserScore does not exist"}');
		header("Content-Type: application/json");
		echo json_encode($result, JSON_PRETTY_PRINT); 
	}
		goto outside;
	}
}


// Get all users scores If not a script $_GET parameter
$scoreAll = new Score();
$scoreResult = $scoreAll->getAllUserScore();

foreach ($scoreResult as &$userEntities) {
     $result[] = Array("UserName" => $userEntities["UserName"], "UserScore" => $userEntities["UserScore"]);
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
header("Content-Type: application/json");
echo json_encode($result, JSON_PRETTY_PRINT);
outside: