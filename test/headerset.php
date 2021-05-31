<?php


//header('HTTP/1.1 401 Unauthorized');
http_response_code(301);
//header("Content-Type: application/json");
//header("Authorization: Bearer 0");
header('Location: http://google.hu');
