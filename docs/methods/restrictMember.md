# restrictMember

[Return to available methods list](index.md)

### Description:

Restricts a member in specifies Chat ID. (Obviously, it doesn't work in a private Chat.)
Note: if user is restricted for more than 366 days or less than 30 seconds from the current time, they are considered to be restricted forever.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|chat_id|Yes|int OR string|
|user_id|Yes|int|
|can\_send_messages|No|boolean|
|can\_send_media|No|boolean|
|can\_send_other|No|boolean|
|can\_send\_web_previews|No|boolean|
|restrict_seconds|No|int|

### Usage:

```php
restrictMember($chat_id, $user_id, $can_send_messages, $can_send_media, $can_send_other, $can_send_web_previews, $restrict_seconds);
```

##### Example:

```php
restrictMember(-123456789, 135792468, true, true, false, false); //user restricted forever
```

Or, if Chat is a supergroup or a channel:

```php
restrictMember("@channelusername", 135792468, true, true, false, false); //user restricted forever
```