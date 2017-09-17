<?php
define("endpoint", "https://api.telegram.org");
$token = $_GET["token"];
$api = "bot".$token;
$bot_version = "1.0"; //please, don't edit this
$settings = json_decode(file_get_contents("DATA/management/settings.json"), true);
$content = file_get_contents("php://input");
$update = json_decode($content, true);
web();
if(empty($update["edited_message"]) == false) $update["message"] = $update["edited_message"];
if(empty($update["channel_post"]) == false) $update["message"] = $update["channel_post"];
if(file_exists("DATA/users/_keep_this")) unlink("DATA/users/_keep_this");
if(file_exists("DATA/groups/_keep_this")) unlink("DATA/groups/_keep_this");
$chatID = $update["message"]["chat"]["id"];
$userID = $update["message"]["from"]["id"];
$msg = $update["message"]["text"];
$username = $update["message"]["from"]["username"];
$name = $update["message"]["from"]["first_name"];
$surname = $update["message"]["from"]["last_name"];
$isBot = $update["message"]["from"]["is_bot"];
$admins = file("DATA/management/admins", FILE_IGNORE_NEW_LINES);
$isAdmin = in_array($userID, $admins);
if($chatID<0)
{
  $title = $update["message"]["chat"]["title"];
  $chatusername = $update["message"]["chat"]["username"];
}
if($update["message"]["reply_to_message"]) {
  $R_chatID = $update["message"]["reply_to_message"]["chat"]["id"];
  $R_userID = $update["message"]["reply_to_message"]["from"]["id"];
  $R_msg = $update["message"]["reply_to_message"]["text"];
  $R_username = $update["message"]["reply_to_message"]["from"]["username"];
  $R_name = $update["message"]["reply_to_message"]["from"]["first_name"];
  $R_surname = $update["message"]["reply_to_message"]["from"]["last_name"];
  $R_msgid = $update["message"]["reply_to_message"]["message_id"];
}
$voice = $update["message"]["voice"]["file_id"];
$photo = $update["message"]["photo"][0]["file_id"];
$document = $update["message"]["document"]["file_id"];
$audio = $update["message"]["audio"]["file_id"];
$sticker = $update["message"]["sticker"]["file_id"];
$caption = $update["message"]["caption"];
$type = $update["message"]["chat"]["type"];
$msgid = $update["message"]["message_id"];
if($update["callback_query"])
{
  $cbid = $update["callback_query"]["id"];
  $cbdata = $update["callback_query"]["data"];
  $cbmid = $update["callback_query"]["message"]["message_id"];
  $chatID = $update["callback_query"]["message"]["chat"]["id"];
  $userID = $update["callback_query"]["from"]["id"];
  $name = $update["callback_query"]["from"]["first_name"];
  $surname = $update["callback_query"]["from"]["last_name"];
  $username = $update["callback_query"]["from"]["username"];
  $type = $update["callback_query"]["message"]["chat"]["type"];
}
if($update["inline_query"])
{
  $iq = $update["inline_query"]["query"];
  $iqid = $update["inline_query"]["id"];
  $userID = $update["inline_query"]["from"]["id"];
  $name = $update["inline_query"]["from"]["first_name"];
  $surname = $update["inline_query"]["from"]["last_name"];
  $username = $update["inline_query"]["from"]["username"];
}
$lang = $update["message"]["from"]["language_code"];
if(stripos(" ".$lang, "-")) {
  $lang = explode("-", $lang);
  $lang = $lang[0];
}

function send_action($chatID, $action) {
  global $api;
  $post = array("chat_id" => $chatID, "action" => $action);
  $curl = curl_init(endpoint.'/'.$api.'/sendChatAction');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function sm($chatID, $msg, $init_keyboard = 0, $type = 0, $parse_mode = 0){
  global $api;
  global $settings;
  if($parse_mode == 0) $parse_mode = $settings["parse_mode"];
  if($settings["send_actions"]) send_action($chatID, "typing");
  if(is_string($init_keyboard) and $init_keyboard == "html" or is_string($init_keyboard) and $init_keyboard == "markdown"){
    $parse_mode = $init_keyboard;
    $init_keyboard = 0;
    $type = 0;
  }
  if($init_keyboard == 0 and $type == 0){
    $post = array("chat_id" => $chatID, "text" => $msg, "parse_mode" => $parse_mode);
    $curl = curl_init(endpoint.'/'.$api.'/sendMessage');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
  if(is_array($init_keyboard) and $type == 1){
    $keyboard = array("keyboard" => $init_keyboard, "resize_keyboard" => true);
    $post = array("chat_id" => $chatID, "text" => $msg, "parse_mode" => $parse_mode, "reply_markup" => json_encode($keyboard));
    $curl = curl_init(endpoint.'/'.$api.'/sendMessage');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
  if(is_array($init_keyboard) and $type == 2){
    $keyboard = array("inline_keyboard" => $init_keyboard);
    $post = array("chat_id" => $chatID, "text" => $msg, "parse_mode" => $parse_mode, "reply_markup" => json_encode($keyboard));
    $curl = curl_init(endpoint.'/'.$api.'/sendMessage');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
	curl_close($curl);
    return $response;
  }
}

function sf($chatID, $doc, $msg, $type){
  global $api;
  $type = strtolower($type);
  if($settings["send_actions"]) send_action($chatID, "upload_$type");
  $post = array("chat_id" => $chatID, $type => $doc, "caption" => $msg);
  $type = ucwords($type);
  $curl = curl_init(endpoint.'/'.$api.'/send'.$type);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function cb_reply($msg, $alert = '', $init_keyboard = 0, $type = 0){
  global $api;
  global $chatID;
  global $cbid;
  global $cbmid;
  global $settings;
  $post = array("callback_query_id" => $cbid, "text" => $alert, "show_alert" => $type);
  $curl = curl_init(endpoint.'/'.$api.'/answerCallbackQuery');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  $post = array();
  if(is_array($init_keyboard)) $keyboard = array("inline_keyboard" => $init_keyboard);
  $post = array("chat_id" => $chatID, "message_id" => $cbmid, "text" => $msg, "parse_mode" => $settings["parse_mode"]);
  if(is_array($keyboard)) $post["reply_markup"] = json_encode($keyboard);
  $curl = curl_init(endpoint.'/'.$api.'/editMessageText');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function iq_reply($iqid, $results, $pm_text = "", $pm_param = ""){
  global $api;
  if($pm_text == ""){
    $post = array("inline_query_id" => $iqid, "results" => json_encode($results), "cache_time" => 20);
  }
  else{
    $post = array("inline_query_id" => $iqid, "results" => json_encode($results), "switch_pm_text" => $pm_text, "switch_pm_parameter" => $pm_param, "cache_time" => 20);
  }
  $curl = curl_init(endpoint.'/'.$api.'/answerInlineQuery');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function forward($fromID, $toID, $msgid){
  global $api;
  $post = array("chat_id" => $toID, "from_chat_id" => $fromID, "message_id" => $msgid);
  $curl = curl_init(endpoint.'/'.$api.'/forwardMessage');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function dm($chatID, $msgid){
  global $api;
  $post = array("chat_id" => $chatID, "message_id" => $msgid);
  $curl = curl_init(endpoint.'/'.$api.'/deleteMessage');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function ban($chatID, $userID){
  global $api;
  $post = array("chat_id" => $chatID, "user_id" => $userID);
  $curl = curl_init(endpoint.'/'.$api.'/kickChatMember');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function unban($chatID, $userID){
  global $api;
  $post = array("chat_id" => $chatID, "user_id" => $userID);
  $curl = curl_init(endpoint.'/'.$api.'/unbanChatMember');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

function kick($chatID, $userID){
  $ban = ban($chatID, $userID);
  $unban = unban($chatID, $userID);
  return "BAN: $ban \n \n UNBAN: $unban";
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
    $send_actions = $settings["send_actions"] ? "true" : "false";
    $maintenance_enabled = $settings["in_maintenance"] ? "true" : "false";
    echo "<head><title>Sysbot Info</title></head>";
    echo "<h1>Sysbot Info</h1>";
    echo "<h3>Version</h3>", "<p>Sysbot v$bot_version</p>";
    echo "<h3>Usage Stats</h3>", "<p>Current users: $users</p>", "<p>Current groups: $groups</p>";
    echo "<h3>Bot Settings</h3>","<p>Parse mode: ".$settings["parse_mode"]."</p>","<p>Actions send: $send_actions</p>","<p>Maintenance mode enabled: $maintenance_enabled (message: ".$settings["maintenance_msg"].")</p>";
    exit;
  }
  elseif($_GET["upgrade"] and $_GET["password"] == hash("sha512", $settings["upgrade_password"])){
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

if($type == "private") touch("DATA/users/$userID");
if($type == "group" or $type == "supergroup") touch("DATA/groups/$chatID");

if($settings["in_maintenance"]){
  sm($chatID, $settings["maintenance_msg"]);
  exit;
}

foreach(iterator_to_array(new FilesystemIterator("ADDONS", FilesystemIterator::SKIP_DOTS)) as $addon) include($addon);
include("commands.php");