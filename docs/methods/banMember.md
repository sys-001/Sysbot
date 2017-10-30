# banMember

[Return to available methods list](index.md)

### Description:

Removes a member from specified Chat ID. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|user_id|Yes|int|
|chat_id|Yes|int OR string|

### Usage:

```php
banMember($user_id, $chat_id);
```

##### Example:

```php
banMember(123456789, -135792468);
```

Or, if Chat is a supergroup or a channel:

```php
banMember(123456789, "@channelusername");