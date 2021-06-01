<?php      

use UserSystem\Components\Member;

$data;

if(isset($searched_name)){
    if($searched_name){

        $memberName = trim($searched_name);

        $member = new Member();

        $checkMember = $member->checkMemberExist($memberName);
        $data = ($checkMember) ? "YES": "NO";    
    }
}

$templateVariables = [
    "data" => $data
];