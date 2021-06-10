<?php 
require_once __DIR__ . '/headerset.php';

header('Content-Type: application/json');
$data = ['Connection' => 'Failed', 'ErrorMessage' => 'Database connection error! ' . $e->getMessage()];
echo json_encode($data, JSON_PRETTY_PRINT);
exit();