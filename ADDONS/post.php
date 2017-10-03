<?PHP

if(strpos($update->message->text, "/post ") === 0 and $isAdmin)
{
sendMessage("Sending message...");
$users_iterator = new FilesystemIterator("DATA/users", FilesystemIterator::SKIP_DOTS);
$groups_iterator = new FilesystemIterator("DATA/groups", FilesystemIterator::SKIP_DOTS);
$users = iterator_to_array($users_iterator);
$groups = iterator_to_array($groups_iterator);

$global_msg = str_replace("/post ", "", $update->message->text);
foreach($users as $user)
{
sendMessage("<b>New broadcast message:</b>

$global_msg", 0, 0, "html", false, str_replace("DATA/users/","",$user));
}
foreach($groups as $group)
{
sendMessage("<b>New broadcast message:</b>

$global_msg", 0, 0, "html", false, str_replace("DATA/users/","",$group));
}
sendMessage("Broadcast message sent.");
}