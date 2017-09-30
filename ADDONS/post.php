<?PHP

if(strpos($update->message->text, "/post ") === 0)
{
sendMessage("Sending message...");
$global_msg = str_replace("/post ", "", $update->message->text);
$groups = scandir("DATA/groups");
$users = scandir("DATA/users");
foreach($users as $user)
{
sendMessage("<b>New broadcast message:</b>

$global_msg", 0, 0, "html", false, $user);
}
foreach($groups as $group)
{
sendMessage("<b>New broadcast message:</b>

$global_msg", 0, 0, "html", false, $group);
}
sendMessage("Broadcast message sent.");
}