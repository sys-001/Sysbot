# sendRequest

[Return to available methods list](index.md)

### Description:

Sends a custom request to the Telegram Bot API.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|method|Yes|string|
|params|Yes|array|

### Usage:

```php
sendRequest($method, $params);
```

##### Example:

```php
sendRequest("foo", array("a" => "b"));
```