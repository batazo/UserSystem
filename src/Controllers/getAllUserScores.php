<?php 

use \UserSystem\Components\Score;

header("Content-Type: application/json");

$scoreAll = new Score();
$scoreResult = $scoreAll->getAllUserScore();

foreach ($scoreResult as &$userEntities) {
     $data[] = Array("UserName" => $userEntities["UserName"], "UserScore" => $userEntities["UserScore"]);
}

$templateVariables = [ 
	"data" => $data
];