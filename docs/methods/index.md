# Available methods

[Return to Sysbot Documentation index](..)

List of available methods:

- [sendRequest($method, $params);](sendRequest.md)

- [sendAction($action, $chat_id);](sendAction.md)

- [sendMessage($text, $keyboard, $type, $parse_mode, $silent, $chat_id);](sendMessage.md)

- [editMessage($message_id, $text, $inline_keyboard, $parse_mode, $chat_id);](editMessage.md)

- [deleteMessage($message_id, $chat_id);](deleteMessage.md)

- [forwardMessage($message_id, $to_chat_id, $from_chat_id);](forwardMessage.md)

- [sendFile($url, $caption, $type, $chat_id);](sendFile.md)

- [answerCallbackQuery($text, $show_as_alert);](answerCallbackQuery.md)

- [answerInlineQuery($result, $switch_to_pm_text, $switch_to_pm_payload);](answerInlineQuery.md)

- [getChat($chat_id);](getChat.md)

- [getChatAdministrators($chat_id);](getChatAdministrators.md)

- [getChatMember($user_id, $chat_id);](getChatMember.md)

- [getChatMembersCount($chat_id);](getChatMembersCount.md)

- [banMember($user_id, $chat_id);](banMember.md)

- [unbanMember($chat_id, $user_id);](unbanMember.md)

- [kickMember($chat_id, $user_id);](kickMember.md)

- [restrictMember($chat_id, $user_id, $can_send_messages, $can_send_media, $can_send_other, $can_send_web_previews, $restrict_seconds);](restrictMember.md)

- [promoteMember($chat_id, $user_id, $can_change_info, $can_delete_messages, $can_invite_users, $can_restrict_members, $can_promote_members);](promoteMember.md)