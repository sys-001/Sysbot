# editMessage

[Return to available methods list](index.md)

### Description:

Edits text of specified Message ID, in specified Chat ID.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|message_id|Yes|int|
|text|Yes|string|
|inline_keyboard|No|array|
|parse_mode|No|string|
|chat_id|No|int|

### Usage:

```php
editMessage($message_id, $text, $inline_keyboard, $parse_mode, $chat_id);
```

##### Example:

```php
editMessage($msgid, "Hello!"); //$$msgid doesn't exist: you must specify a valid message ID in order to edit that message.
```

Or, in case you want to edit a message after a callback query:

```php
editMessage($update->callback_query->message->message_id, "Hello!");
```