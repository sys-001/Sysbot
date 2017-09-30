<?php

define("endpoint", "https://api.telegram.org");
$bot_version = "1.0"; //please, don't edit this
$token = "bot".$_GET["token"];
$settings = json_decode(file_get_contents("DATA/management/settings.json"));
$settings->test_mode ? $token = $token."/test" : $token = $token;
$update = json_decode(file_get_contents("php://input"));
web();
$admins = file("DATA/management/admins", FILE_IGNORE_NEW_LINES);
if(file_exists("DATA/users/_keep_this")) unlink("DATA/users/_keep_this");
if(file_exists("DATA/groups/_keep_this")) unlink("DATA/groups/_keep_this");
if(!empty($update->message->reply_to_message)) $update->message = $update->message->reply_to_message;
$isAdmin = in_array($update->message->from->id, $admins) or in_array($update->callback_query->from->id, $admins);

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
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  $post = array("chat_id" => $chat_id, "action" => $action);
  return sendRequest("sendChatAction", $post);
}

function sendMessage($msg, $init_keyboard = 0, $type = 0, $parse_mode = 0, $silent = false, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  if($parse_mode == 0) $parse_mode = $settings->parse_mode;
  if($settings->send_actions) sendAction($chatID, "typing");
  if($init_keyboard == 0 and $type == 0) $post = array("chat_id" => $chat_id, "text" => $msg, "parse_mode" => $parse_mode);
  if(is_array($init_keyboard) and $type == 1){
    $keyboard = array("keyboard" => $init_keyboard, "resize_keyboard" => true);
    $post = array("chat_id" => $chat_id, "text" => $msg, "parse_mode" => $parse_mode, "reply_markup" => json_encode($keyboard));
  }
  if(is_array($init_keyboard) and $type == 2){
    $keyboard = array("inline_keyboard" => $init_keyboard);
    $post = array("chat_id" => $chat_id, "text" => $msg, "parse_mode" => $parse_mode, "reply_markup" => json_encode($keyboard));
  }
  return sendRequest("sendMessage", $post);
}

function editMessage($msgid, $msg, $inline_keyboard = 0, $parse_mode = 0, $chat_id = 0){
  global $update;
  global $settings;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  if($parse_mode == 0) $parse_mode = $settings->parse_mode;
  if($settings->send_actions) sendAction($chatID, "typing");
  $post = array("chat_id" => $chat_id, "message_id" => $msgid, "text" => $msg, "parse_mode" => $parse_mode);
  if(is_array($init_keyboard)) $keyboard = array("inline_keyboard" => $init_keyboard);
  if(is_array($keyboard)) $post["reply_markup"] = json_encode($keyboard);
  return sendRequest("editMessageText", $post);
}

function deleteMessage($msgid, $chat_id = 0){
  global $update;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  $post = array("chat_id" => $chat_id, "message_id" => $msgid);
  return sendRequest("deleteMessage", $post);
}

function forwardMessage($msgid, $to_id, $from_id = 0){
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  $post = array("chat_id" => $toID, "from_chat_id" => $fromID, "message_id" => $msgid);
  return sendRequest("forwardMessage", $post);
}

function sendFile($doc, $msg, $type, $chat_id = 0){
  global $update;
  if($chat_id == 0) !empty($update->message) ? $chat_id = $update->message->chat->id : $chat_id = $update->callback_query->chat->id;
  $type = strtolower($type);
  if($settings->send_actions) sendAction($chatID, "upload_$type");
  $post = array("chat_id" => $chatID, $type => $doc, "caption" => $msg);
  $type = ucwords($type);
  return sendRequest("send".$type, $post);
}

function answerCallbackQuery($msg, $show_as_alert = false){
  global $update;
  global $settings;
  if($chat_id == 0) $chat_id = $update->callback_query->chat->id;
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

function web(){
  global $update;
  global $settings;
  global $bot_version;
  if($_GET["info"]) {
  	$users = getUsers();
    $groups = getGroups();
    $send_actions = $settings->send_actions ? "true" : "false";
    $maintenance_enabled = $settings->in_maintenance ? "true" : "false";
    echo "<head><title>Sysbot Info</title></head>";
    echo "<h1>Sysbot Info</h1>";
    echo "<h3>Version</h3>", "<p>Sysbot v$bot_version</p>";
    echo "<h3>Usage Stats</h3>", "<p>Current users: $users</p>", "<p>Current groups: $groups</p>";
    echo "<h3>Bot Settings</h3>","<p>Parse mode: ".$settings->parse_mode."</p>","<p>Actions send: $send_actions</p>","<p>Maintenance mode enabled: $maintenance_enabled (message: ".$settings->maintenance_msg.")</p>";
    exit;
  }
  elseif($_GET["upgrade"] and $_GET["password"] == hash("sha512", $settings->upgrade_password)){
  	echo "<head><title>Sysbot Upgrade</title></head>";
    echo "<h1>Sysbot Upgrade</h1>", "<p>Current Version: v$bot_version</p>";
	$remote_version = file_get_contents("http://sysbot.altervista.org/index.php?check=true");
	if(version_compare($bot_version, $remote_version) < 0){
      $remote_bot = file_get_contents("http://sysbot.altervista.org/index.php?upgrade=true");
	  echo "<b>Sysbot upgraded to v$remote_version</b>";
	  file_put_contents("bot.php", $remote_bot);
	} 
	else{
	  echo "<b>Sysbot is up-to-date.</b>";
	}
    exit;
  }
  if(!$update){
    header(':', true, 401);
    exit;
  }
}

if($update->message->chat->type == "private") touch("DATA/users/$userID");
if($update->message->chat->type == "group" or $update->message->chat->type == "supergroup") touch("DATA/groups/$chatID");

if($settings->in_maintenance){
  sendMessage($settings->maintenance_msg);
  exit;
}

foreach(iterator_to_array(new FilesystemIterator("ADDONS", FilesystemIterator::SKIP_DOTS)) as $addon) include($addon);
include("commands.php");