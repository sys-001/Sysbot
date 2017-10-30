# kickMember

[Return to available methods list](index.md)

### Description:

Equivals to banMember + unbanMember. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|user_id|Yes|int|
|chat_id|Yes|int OR string|

### Usage:

```php
kickMember($user_id, $chat_id);
```

##### Example:

```php
kickMember(123456789, -135792468);
```

Or, if Chat is a supergroup or a channel:

```php
kickMember(123456789, "@channelusername");