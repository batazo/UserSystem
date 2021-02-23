<?php

if(isset($_SERVER["HTTP_REFERER"])){
    $restprefix = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    
    $rest = $restprefix . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } else {
         $rest = "*";
    }
    
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Origin: ' . $rest . '');