<?php 
$systenendpoints = [
    'Connection' => 'Success',
    'root' => 'api',
    'login' => 'api/login',
    'register' => 'api/register',
    'jwt_profile' => 'api/user',
    'session_profile' => 'api/userprofile',
    'local_profile' => 'api/profile',
    'all_user_scores' => 'api/userscore',
    'user_score_by_name' => 'api/userscore/',
    'membercheck' => 'api/membercheck/'
];

header("Content-Type: application/json");
echo json_encode($systenendpoints, JSON_PRETTY_PRINT);