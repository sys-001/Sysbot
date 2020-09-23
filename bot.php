<?php

use Sysbot\{Bot, Exceptions\SecurityException};

require_once __DIR__ . '/vendor/autoload.php';

$token = '123456789:AAInsert123_Your456Token-Here789';

try {
    $bot = new Bot($token);
} catch (SecurityException $e) {
    exit(1);
}

foreach ($bot->iterUpdates() as $update) { //insert your code here
    echo json_encode($update) . PHP_EOL;
}