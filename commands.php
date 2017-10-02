<?PHP

//I've included some sample code, so you can understand this better.

if($update->message->text == "/start"){
	sendMessage("Sample response.");
}

if($update->message->text == "/rstart"){
	$menu[] = array("Button 1", "Button 2");
	$menu[] = array("Button 3");
	sendMessage("Sample response with Reply Keyboard.", $menu, 1);
}

if($update->message->text == "/istart"){
	$menu[] = array(array("text" => "Button 1", "callback_data" => "button_1"), array("text" => "Button 2", "callback_data" => "button_2"));
	$menu[] = array(array("text" => "Button 3", "callback_data" => "button_3"));
	sendMessage("Sample response with Inline Keyboard.", $menu, 2);
}

if($update->inline_query->query == "Test"){ //inline mode. Note: it works only if Inline Mode is enabled on your bot.
	$test[] = array('type' => 'article', 'id' => '001' , 'title' => 'Sample title', 'description' => 'Sample description', 'message_text' => 'Sample article');
	answerInlineQuery($test, "Contact me in private chat", "payload");
}

if($update->message->text == "/usage" and $isAdmin){ //sample command that works only for IDs written in 'DATA/management/admins'
	sendMessage("Bot is used by ".getUsers()." users and ".getGroups()." groups.");
}

if($update->callback_query->data == "button_1"){
	sendMessage("Sample response from Button 1.");
}

if($update->callback_query->data == "button_2"){
	answerCallbackQuery("Sample toast from Button 2.");
}

if($update->callback_query->data == "button_3"){
	answerCallbackQuery("Sample alert from Button 3.", 1);
}