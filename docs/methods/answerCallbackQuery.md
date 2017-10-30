# answerCallbackQuery

[Return to available methods list](index.md)

### Description:

Prints specified message in specified in Chat ID, using a toast or a dialog.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|text|Yes|string|
|show\_as_alert|No|boolean|
|chat_id|No|int|

### Usage:

```php
answerCallbackQuery($text, $show_as_alert, $chat_id);
```

##### Example:

```php
answerCallbackQuery("Sample response in a toast.");
```

Or, if you want to use a dialog:

```php
answerCallbackQuery("Sample response in a dialog.", true);
```