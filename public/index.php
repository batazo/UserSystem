<?php

use UserSystem\Components\DataSource;
use UserSystem\Components\Member;
use UserSystem\Components\Score;

require_once __DIR__ . "/../vendor/autoload.php";

$dataSource = new DataSource();
$member = new Member();
$score = new Score();

dump($dataSource);
dump($member);
dump($score);

exit;
