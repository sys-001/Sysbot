# getChat

[Return to available methods list](index.md)

### Description:

Returns all available informations about specified Chat ID.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|chat_id|Yes|int OR string|

### Usage:

```php
getChat($chat_id);
```

##### Example:

```php
getChat(123456789);
```

Or, if Chat is a supergroup or a channel:

```php
getChat("@channelusername");
```