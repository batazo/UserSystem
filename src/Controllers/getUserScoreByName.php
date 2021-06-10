<?php 

use \UserSystem\Components\Score;

header("Content-Type: application/json");

$data = Array(
    "Connection" => "Success",
    "UserName" => "UserName does not exist",
    "UserScore" => "UserScore does not exist"
);

$score = new Score();

$scoreResultByUNAME = $score->getScoreByUserName($searchedScoreName);

if(isset($scoreResultByUNAME[0]["UserName"])) {
    $data = Array(
        "Connection" => "Success",
        "UserName" => $scoreResultByUNAME[0]["UserName"],
        "UserScore" => $scoreResultByUNAME[0]["UserScore"]);
}

$templateVariables = [ 
	"data" => $data
];