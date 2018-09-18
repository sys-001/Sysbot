<?php

require_once "res/core.php";

define("TOKEN", "123456:BOT_TOKEN_HERE");
define("SETTINGS_PATH", "data/management/bot.settings");

$Bot = new TelegramBot(TOKEN, SETTINGS_PATH);

// will be implemented soon
/**if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

$allowed_addresses = array();
for($i = 197; $i <= 233; $i++){
	$allowed_addresses[] = "149.154.167.$i";
}

if(!in_array($ip, $allowed_addresses)){
	if(hash("sha512", $_GET["administration"]) == $Bot->settings->general->administration_password){
		echo "Future implementation.";
	} else {
		echo "Unauthorized.";
		exit;
	}
}**/

$Bot->setHook("message::text", "/start", function($Bot){
    $Bot->sendMessage("Hi!");
});
$Bot->start();