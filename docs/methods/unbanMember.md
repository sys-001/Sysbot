# unbanMember

[Return to available methods list](index.md)

### Description:

Unbans a member from specified Chat ID. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|user_id|Yes|int|
|chat_id|Yes|int OR string|

### Usage:

```php
unbanMember($user_id, $chat_id);
```

##### Example:

```php
unbanMember(123456789, -135792468);
```

Or, if Chat is a supergroup or a channel:

```php
unbanMember(123456789, "@channelusername");