# deleteMessage

[Return to available methods list](index.md)

### Description:

Deletes specified Message ID from specified Chat ID.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|message_id|Yes|int|
|chat_id|No|int|

### Usage:

```php
deleteMessage($message_id, $chat_id);
```

##### Example:

```php
deleteMessage($msgid); //$msgid doesn't exist: you must specify a valid message ID in order to edit that message.
```