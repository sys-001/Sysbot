<?PHP 

if($update->message->chat->id > 0 and !$isAdmin){
  $custom_offset = $settings->antiflood->ban_minutes * 60;
  if(file_exists("DATA/antiflood/".$update->message->chat->id." ban")){
    $ban_time = file_get_contents("DATA/antiflood/".$update->message->chat->id." ban");
	$ban_check = time() - $ban_time;
    $ban_check >=  $custom_offset ? unlink("DATA/antiflood/".$update->message->chat->id." ban") : exit;
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
  if($messages_number > $settings->antiflood->messages_number and $flood_check < $settings->antiflood->seconds){
    sendMessage($settings->antiflood->ban_message);
    file_put_contents("DATA/antiflood/".$update->message->chat->id." ban", time());
    unlink("DATA/antiflood/".$update->message->chat->id." msg");
    unlink("DATA/antiflood/msg");
    exit;
}
}
}