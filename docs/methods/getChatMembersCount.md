# getChatMembersCount

[Return to available methods list](index.md)

### Description:

Returns the number of members in a Chat. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|chat_id|Yes|int OR string|

### Usage:

```php
getChatMembersCount($chat_id);
```

##### Example:

```php
getChatMembersCount(-123456789);
```

Or, if Chat is a supergroup or a channel:

```php
getChatMembersCount("@channelusername");
```