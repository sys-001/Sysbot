# sendAction

[Return to available methods list](index.md)

### Description:

Sends an action to target Chat ID. See available actions from this list:

| Action | Telegram Status |
|--------|-----------------|
|typing|Bot is typing...|
|upload_photo|Bot is sending photo...|
|upload_video|Bot is sending video...|
|record_video|Bot is recording video...|
|upload_audio|Bot is sending audio...|
|record_audio|Bot is recording audio...|
|upload_document|Bot is sending file...|
|find_location|Bot is picking location...|
|upload_video_note|Bot is sending video...|
|record_video_note|Bot is recording video...|

### Parameters:

| Name | Required | Type |
|------|----------|------|
|action|Yes|string|
|chat_id|No|int|

### Usage:

```php
sendAction($action, $chat_id);
```

##### Example:

```php
sendAction("typing");
```

Or, in case of a new message:

```php
sendAction("typing", $update->message->chat->id);
```

Or, in case of a new callback query:

```php
sendAction("typing", $update->callback_query->message->chat->id);
```