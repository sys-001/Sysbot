# getChatAdministrators

[Return to available methods list](index.md)

### Description:

Returns a list of Chat administrators. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|chat_id|Yes|int OR string|

### Usage:

```php
getChatAdministrators($chat_id);
```

##### Example:

```php
getChatAdministrators(-123456789);
```

Or, if Chat is a supergroup or a channel:

```php
getChatAdministrators("@channelusername");
```