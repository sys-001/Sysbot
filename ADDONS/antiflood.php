<?PHP 
if(file_exists("DATA/antiflood/.keep_this")) unlink("DATA/antiflood/.keep_this");
 //Use your custom settings here
$antiflood_settings = array(
"seconds" => 1,
"messages_number" => 3,
"ban_minutes" => 2,
"ban_message" => "Flood detected, you're banned from using the bot for 2 minutes."
);
if($update->message->chat->id > 0 and $isAdmin == false){
  $custom_offset = $antiflood_settings["ban_minutes"] * 60;
  if(file_exists("DATA/antiflood/".$update->message->chat->id." ban")){
    $ban_time = file_get_contents("DATA/antiflood/".$update->message->chat->id." ban");
	$ban_check = time() - $ban_time;
    $ban_check >=  $ban_offset ? unlink("DATA/antiflood/".$update->message->chat->id." ban") : exit;
  }
  else{
  $last_check = file_get_contents("DATA/antiflood/".$update->message->chat->id);
  file_put_contents("DATA/antiflood/".$update->message->chat->id, time());
  $messages_number = file_get_contents("DATA/antiflood/".$update->message->chat->id." msg") + 1;
  file_put_contents("DATA/antiflood/".$update->message->chat->id." msg", $messages_number);
  $flood_check = time() - $last_check;
  if($flood_check < 0){
    unlink("DATA/antiflood/".$update->message->chat->id." msg");
    $antiflood = 1;
  }
  if($messages_number > $antiflood_settings["messages_number"] and $flood_check < $settings["seconds"]){
    sendMessage($antiflood_settings["ban_message"]);
    file_put_contents("DATA/antiflood/".$update->message->chat->id." ban", time());
    unlink("DATA/antiflood/".$update->message->chat->id." msg");
    unlink("DATA/antiflood/msg");
    exit;
}
}
}