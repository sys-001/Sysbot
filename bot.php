<?php

require_once "res/core.php";

define("TOKEN", "123456:BOT_TOKEN_HERE");
define("SETTINGS_PATH", "data/management/bot.settings");

$Bot = new TelegramBot(TOKEN, SETTINGS_PATH);

if(!empty($_SERVER['HTTP_CLIENT_IP'])){
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
}

if($Bot->use_polling){
	$offset = 0;
	foreach($Bot->settings->general->admins as $admin) $Bot->sendMessage("Bot started correctly.", $admin);
	while(true){
		$response = $Bot->getUpdates(['offset' => $offset]);
        foreach($response->result as $update){
			$Bot->update = $update;
			$offset = $update->update_id;
			$is_admin = in_array($update->message->from->id, $Bot->settings->admins) or in_array($update->callback_query->from->id, $Bot->settings->admins) or in_array($update->inline_query->from->id, $Bot->settings->admins);
			if($Bot->settings->maintenance->enabled and $update->message->chat->type == "private"){
				$Bot->sendMessage($Bot->settings->maintenance->message);
				continue;
			}
			if($update->message->text == "/halt"){
				$Bot->sendMessage("Shutting down...");
				$offset++;
				$Bot->getUpdates(['offset' => $offset,
				'limit' => 1
				]);
				exit;
			}
        }
		include "commands.php";
        $offset++;
	}
} else {
	$update = $Bot->update;
	$is_admin = in_array($update->message->from->id, $Bot->settings->admins) or in_array($update->callback_query->from->id, $Bot->settings->admins) or in_array($update->inline_query->from->id, $Bot->settings->admins);
	include "commands.php";
}