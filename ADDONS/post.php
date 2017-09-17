<?PHP

if(strpos($msg, "/post ") === 0)
{
sm($chatID, "Sending message...");
$global_msg = str_replace("/global ", "", $msg);
$groups = scandir("DATA/groups");
$users = scandir("DATA/users");
foreach($users as $user)
{
sm($user, "<b>New broadcast message:</b>

$global_msg", "html");
}
foreach($groups as $group)
{
sm($group, "<b>New broadcast message:</b>

$global_msg", "html");
}
sm($chatID, "Broadcast message sent.");
}