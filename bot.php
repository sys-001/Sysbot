<?php

define("endpoint", "https://api.telegram.org");
define("bot_version", "1.1");
if(!file_exists("DATA/management/settings.json")){
	if(file_exists("setup.php")){
		echo 'Unable to locate Sysbot settings. <a href="setup.php">Click here</a> to run setup.';
	}
	else{
		$curl = curl_init("https://raw.githubusercontent.com/sys-001/Sysbot/master/setup.php");
		curl_setopt($curl, CURLOPT_TIMEOUT, 50);
		curl_setopt($curl, CURLOPT_FILE, fopen('setup.php', 'w+'));
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($curl);
		curl_close($curl);
		echo 'Unable to locate Sysbot settings. <a href="setup.php">Click here</a> to re-run setup.';
	}
	exit;
}
$settings = json_decode(file_get_contents("DATA/management/settings.json"));

function sendRequest($method, $params){
  global $token;
  $curl = curl_init(endpoint."/".$token."/".$method);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function sendAction($action, $chat_id = 0) {
  global $update;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->message->chat->id;
  $post = array("chat_id" => $chat_id, "action" => $action);
  return sendRequest("sendChatAction", $post);
}

function sendMessage($msg, $init_keyboard = 0, $type = 0, $parse_mode = 0, $show_preview = true, $silent = false, $reply_to_message_id = 0, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->message->chat->id;
  if($parse_mode == 0) $parse_mode = $settings->parse_mode;
  if($settings->send_actions) sendAction($chat_id, "typing");
  $post = array("chat_id" => $chat_id, "text" => $msg, "parse_mode" => $parse_mode, "disable_web_page_preview" => !$show_preview, "disable_notification" => $silent);
  if($reply_to_message_id != 0) $post["reply_to_message_id"] = $reply_to_message_id;
  
  if(is_array($init_keyboard) and $type == 1){
    $keyboard = array("keyboard" => $init_keyboard, "resize_keyboard" => true);
    $post["reply_markup"] = json_encode($keyboard);
  }
  if(is_array($init_keyboard) and $type == 2){
    $keyboard = array("inline_keyboard" => $init_keyboard);
    $post["reply_markup"] = json_encode($keyboard);
  }
  return sendRequest("sendMessage", $post);
}

function editMessage($msgid, $msg, $inline_keyboard = 0, $parse_mode = 0, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->message->chat->id;
  if($parse_mode == 0) $parse_mode = $settings->parse_mode;
  if($settings->send_actions) sendAction($chat_id, "typing");
  $post = array("chat_id" => $chat_id, "message_id" => $msgid, "text" => $msg, "parse_mode" => $parse_mode);
  if(is_array($inline_keyboard)) $keyboard = array("inline_keyboard" => $inline_keyboard);
  if(is_array($keyboard)) $post["reply_markup"] = json_encode($keyboard);
  return sendRequest("editMessageText", $post);
}

function deleteMessage($msgid, $chat_id = 0){
  global $update;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->message->chat->id;
  $post = array("chat_id" => $chat_id, "message_id" => $msgid);
  return sendRequest("deleteMessage", $post);
}

function forwardMessage($msgid, $from_id, $to_id = 0){
  global $update;
  if($to_id == 0) !empty($update->message) ? $to_id = $update->message->chat->id : $to_id = $update->callback_query->message->chat->id;
  $post = array("chat_id" => $to_id, "from_chat_id" => $from_id, "message_id" => $msgid);
  return sendRequest("forwardMessage", $post);
}

function sendFile($doc, $msg, $type, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->message->chat->id;
  $type = strtolower($type);
  if($settings->send_actions) sendAction($chat_id, "upload_$type");
  $post = array("chat_id" => $chat_id, $type => $doc, "caption" => $msg);
  $type = ucwords($type);
  return sendRequest("send".$type, $post);
}

function answerCallbackQuery($msg, $show_as_alert = false, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) $chat_id = $update->callback_query->message->chat->id;
  $post = array("callback_query_id" => $update->callback_query->id, "text" => $msg, "show_alert" => $show_as_alert);
  return sendRequest("answerCallbackQuery", $post);
}

function answerInlineQuery($result, $pm_text = "", $pm_param = ""){
  global $update;
  $post = array("inline_query_id" => $update->inline_query->id, "results" => json_encode($result), "cache_time" => 20);
  if($pm_text != ""){
    $post["switch_pm_text"] = $pm_text;
	$post["switch_pm_parameter"] = $pm_param;
  }
  return sendRequest("answerInlineQuery", $post);
}

function getChat($chat_id){
  $post = array("chat_id" => $chat_id);
  return sendRequest("getChat", $post);
}

function getChatAdministrators($chat_id){
  $post = array("chat_id" => $chat_id);
  return sendRequest("getChatAdministrators", $post);
}

function getChatMember($user_id, $chat_id){
  $post = array("chat_id" => $chat_id, "user_id" => $user_id);
  return sendRequest("getChatMember", $post);
}

function getChatMembersCount($chat_id){
  $post = array("chat_id" => $chat_id);
  return sendRequest("getChatMembersCount", $post);
}

function banMember($user_id, $chat_id){
  $post = array("chat_id" => $chat_id, "user_id" => $user_id);
  return sendRequest("kickChatMember", $post);
}

function unbanMember($chat_id, $user_id){
  $post = array("chat_id" => $chat_id, "user_id" => $user_id);
  return sendRequest("unbanChatMember", $post);
}

function kickMember($chat_id, $user_id){
  $ban = banMember($chat_id, $user_id);
  $unban = unbanMember($chat_id, $user_id);
  return "BAN: $ban \n \n UNBAN: $unban";
}

function restrictMember($chat_id, $user_id, $can_send_messages = false, $can_send_media = false, $can_send_other = false, $can_send_web_previews = false, $restrict_seconds = 0){
  $post = array("chat_id" => $chat_id, "user_id" => $user_id, "can_send_messages" => $can_send_messages, "can_send_media_messages" => $can_send_media, "can_send_other_messages" => $can_send_other, "can_add_web_page_previews" => $can_send_web_previews);
  if($restrict_seconds > 0) $post["until_date"] = time() + $restrict_seconds; 
  return sendRequest("restrictChatMember", $post);
}

function promoteMember($chat_id, $user_id, $can_change_info = false, $can_delete_messages = false, $can_invite_users = false, $can_restrict_members = false, $can_promote_members = false){
  $post = array("chat_id" => $chat_id, "user_id" => $user_id, "can_change_info" => $can_change_info, "can_delete_messages" => $can_delete_messages, "can_invite_users" => $can_invite_users, "can_restrict_members" => $can_restrict_members, "can_promote_members" => $can_promote_members);
  return sendRequest("promoteChatMember", $post);
}

function getUsers(){
	$usercount = new FilesystemIterator("DATA/users", FilesystemIterator::SKIP_DOTS);
    return iterator_count($usercount);
}

function getGroups(){
	$usercount = new FilesystemIterator("DATA/groups", FilesystemIterator::SKIP_DOTS);
    return iterator_count($usercount);
}

if($_GET["info"]) {
	$users = getUsers();
    $groups = getGroups();
    $send_actions = $settings->send_actions ? "true" : "false";
    $maintenance_enabled = $settings->in_maintenance ? "true" : "false";
	$getUpdates_enabled = $settings->getUpdates->enabled ? "true" : "false";
    echo "<head><title>Sysbot Info</title></head>";
    echo "<center><h1>Sysbot Info</h1>";
    echo "<h3>Usage Stats</h3>", "<p>Current users: $users</p>", "<p>Current groups: $groups</p>";
    echo "<h3>Bot Settings</h3>","<p>Parse mode: ".$settings->parse_mode."</p>","<p>Actions send: $send_actions</p>","<p>Maintenance mode enabled: $maintenance_enabled (message: ".$settings->maintenance_msg.")</p>", "<p>GetUpdates mode: $getUpdates_enabled</p>";
    echo "<h3>Version</h3>", "<p>Sysbot v".bot_version."</p>";
    $remote_version = file_get_contents("https://raw.githubusercontent.com/sys-001/Sysbot/master/.ver");
	echo version_compare(bot_version, $remote_version) < 0 ? "<b>A new update is available; if you wish to download it, you must enter your Upgrade Password.</b><br><br><form action='#' method='post'>Upgrade password:<br><input type='password' name='upgrade_password'/><br><br><input type='submit' name='submit' value='Start upgrade'/></form>" : "<b>Sysbot is up-to-date.</b>";
	echo "</center>";
	exit;
}
elseif($_POST["upgrade"]){
	echo "<head><title>Sysbot Upgrade</title></head>";
    echo "<h1>Sysbot Upgrade</h1>", "<p>Current Version: v".bot_version."</p>";
	if(sha512($_POST["upgrade_password"]) != $settings->upgrade_password) echo "<b>Password validation failed!</b>" && exit;
	$remote_version = file_get_contents("https://raw.githubusercontent.com/sys-001/Sysbot/master/.ver");
	if(version_compare(bot_version, $remote_version) < 0){
      file_put_contents("bot_upgrade.zip", file_get_contents("https://github.com/sys-001/Sysbot/archive/master.zip"));
	  $zip = new ZipArchive;
	  $res = $zip->open("bot_upgrade.zip");
	  if ($res === TRUE) {
		  $zip->extractTo("bot_upgrade");
		  $zip->close();
		  foreach(iterator_to_array(new FilesystemIterator("ADDONS", FilesystemIterator::SKIP_DOTS)) as $addon) unlink($addon);
		  foreach(iterator_to_array(new FilesystemIterator("bot_upgrade/ADDONS", FilesystemIterator::SKIP_DOTS)) as $new_addon) copy($new_addon, "ADDONS/".str_replace("bot_upgrade/ADDONS/", "", $new_addon));
		  unlink("bot.php");
		  copy("bot_upgrade/bot.php", "bot.php");
		  $temp_files = glob("bot_upgrade/*");
		  foreach($temp_files as $temp_file){
			  if(is_file($file)){
				  unlink($file);
			  }
		  rmdir("bot_upgrade");
		  unlink("bot_upgrade.zip");
		  echo "<b>Sysbot upgraded to v$remote_version</b>";
		  }
		}
	else{
	  echo "<b>Sysbot is up-to-date.</b>";
	}
    exit;
	}
}

if($settings->getUpdates->enabled){
	require("crypto.php");
	$settings->test_mode ? $token = "bot".$getUpdates_token."/test" : $token = "bot".$getUpdates_token;
	while(true){
		$last_offset = file_get_contents("DATA/management/offset");
		empty($last_offset) ? $current_offset = 1 : $current_offset = intval($last_offset) + 1;
		file_put_contents("DATA/management/offset", $current_offset);
		$update = json_decode(sendRequest("getUpdates", array("offset" => $current_offset)));
		if(!empty($update->message->reply_to_message)) $update->message = $update->message->reply_to_message;
		$isAdmin = in_array($update->message->from->id, $settings->admins) or in_array($update->callback_query->from->id, $settings->admins);
		if($update->message->chat->type == "private") touch("DATA/users/".$update->message->chat->id);
		if($update->message->chat->type == "group" or $update->message->chat->type == "supergroup") touch("DATA/groups/".$update->message->chat->id);
		if($settings->in_maintenance and $update->message->chat->type == "private") sendMessage($settings->maintenance_msg) && exit;
		foreach(iterator_to_array(new FilesystemIterator("ADDONS", FilesystemIterator::SKIP_DOTS)) as $addon) include($addon);
		include("commands.php");
	}
}
else{
	$settings->test_mode ? $token = $token = "bot".$_GET["token"]."/test" : $token = "bot".$_GET["token"];
	$update = json_decode(file_get_contents("php://input"));
	if(!empty($update->message->reply_to_message)) $update->message = $update->message->reply_to_message;
	$isAdmin = in_array($update->message->from->id, $settings->admins) or in_array($update->callback_query->from->id, $settings->admins);
	if($update->message->chat->type == "private") touch("DATA/users/".$update->message->chat->id);
	if($update->message->chat->type == "group" or $update->message->chat->type == "supergroup") touch("DATA/groups/".$update->message->chat->id);
	if($settings->in_maintenance and $update->message->chat->type == "private") sendMessage($settings->maintenance_msg) && exit;
	foreach(iterator_to_array(new FilesystemIterator("ADDONS", FilesystemIterator::SKIP_DOTS)) as $addon) include($addon);
	include("commands.php");
}
