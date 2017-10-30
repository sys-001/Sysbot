# forwardMessage

[Return to available methods list](index.md)

### Description:

Forwards specified Message ID from specified Chat ID to specified Chat ID. If target Chat ID is not specified, message will be forwarded to current Chat.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|message_id|Yes|int|
|from\_chat_id|Yes|int|
|to\_chat_id|No|int|

### Usage:

```php
forwardMessage($message_id, $from_chat_id, $to_chat_id);
```

##### Example:

```php
forwardMessage($msgid, 123456789); //$msgid doesn't exist: you must specify a valid message ID in order to edit that message.
```