# getChatMember

[Return to available methods list](index.md)

### Description:

Returns all available informations of a Chat member. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|user_id|Yes|int|
|chat_id|Yes|int OR string|

### Usage:

```php
getChatMember($user_id, $chat_id);
```

##### Example:

```php
getChatMember(123456789, -135792468);
```

Or, if Chat is a supergroup or a channel:

```php
getChatMember(123456789, "@channelusername");
```