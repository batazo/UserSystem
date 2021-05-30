<?php 
$systenendpoints = [
    'root' => 'api',
    'login' => 'api/login',
    'register' => 'api/register',
    'profile' => 'api/profile',
    'json_profile' => 'api/userprofile',
    'all_user_scores' => 'api/userscore',
    'user_score_by_name' => 'api/userscore/',
    'membercheck' => 'api/membercheck/'
];

header("Content-Type: application/json");
echo json_encode($systenendpoints, JSON_PRETTY_PRINT);