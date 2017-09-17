<?PHP

//I've included some sample code, so you can understand this better.

if($msg == "/start"){
	sm($chatID, "Sample response.");
}

if($msg == "/rstart"){
	$menu[] = array("Button 1", "Button 2");
	$menu[] = array("Button 3");
	sm($chatID, "Sample response with Reply Keyboard.", $menu, 1);
}

if($msg == "/istart"){
	$menu[] = array(array("text" => "Button 1", "callback_data" => "button_1"), array("text" => "Button 2", "callback_data" => "button_2"));
	$menu[] = array(array("text" => "Button 3", "callback_data" => "button_3"));
	sm($chatID, "Sample response with Inline Keyboard.", $menu, 2);
}

if($iqid == "Test"){ //inline mode. Note: it works only if Inline Mode is enabled on your bot.
	$test[] = array('type' => 'article', 'id' => '001' , 'title' => 'Sample title', 'description' => 'Sample description', 'message_text' => 'Sample article');
	iq_reply($iqid, $test, "Contact me in private chat", "payload");
}

if($msg == "/usage" and $isAdmin){ //sample command that works only for IDs written in 'DATA/management/admins'
	sm($chatID, "Bot is used by ".getUsers()." users and ".getGroups()." groups.");
}

if($cbid == "button_1"){
	sm($chatID, "Sample response from Button 1.");
}

if($cbid == "button_2"){
	cb_reply("Sample message edited from Button 2.");
}

if($cbid == "button_3"){
	cb_reply("Sample message edited (with alert) from Button 3.", "Sample alert from Button 3.", 0, 1);
}