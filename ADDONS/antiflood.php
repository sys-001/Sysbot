<?PHP 
if(file_exists("DATA/antiflood/_keep_this")) unlink("DATA/antiflood/_keep_this");


 //Use your custom settings here
$antiflood_settings = array(
"seconds" => 2,
"messages_number" => 4,
"ban_minutes" => 2,
"ban_message" => "Flood detected, you're banned from using the bot for ".$antiflood_settings["ban_minutes"]." minutes."
);

if($chatID > 0 and $isAdmin == false){
  $custom_offset = $antiflood_settings["ban_minutes"] * 60;
  if(file_exists("DATA/antiflood/$userID ban")){
    $ban_time = file_get_contents("DATA/antiflood/$userID ban");
	$ban_check = time() - $ban_time;
    $ban_check >=  $ban_offset ? unlink("DATA/antiflood/$userID ban") : exit;
  }
  else{
  $last_check = file_get_contents("DATA/antiflood/$userID");
  file_put_contents("DATA/antiflood/$userID", time());
  $messages_number = file_get_contents("DATA/antiflood/$userID msg") + 1;
  file_put_contents("DATA/antiflood/$userID msg", $messages_number);
  $flood_check = time() - $last_check;
  if($flood_check < 0){
    unlink("DATA/antiflood/$userID msg");
    $antiflood = 1;
  }
  if($messages_number > $antiflood_settings["messages_number"] and $flood_check < $custom_offset){
    sm($chatID, $antiflood_settings["ban_message"]);
    file_put_contents("DATA/antiflood/$userID ban", time());
    unlink("DATA/antiflood/$userID msg");
    unlink("DATA/antiflood/msg");
    exit;
}
}
}