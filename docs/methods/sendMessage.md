# sendMessage

[Return to available methods list](index.md)

### Description:

Sends a message to target Chat ID.

### Parameters:

| Name | Required | Type |
|------|----------|------|
|text|Yes|string|
|keyboard|No|array|
|type|No|int|
|parse_mode|No|string|
|silent|No|boolean|
|chat_id|No|int|

### Usage:

```php
sendMessage($text, $keyboard, $type, $parse_mode, $silent, $chat_id);
```

##### Example:

```php
sendMessage("Hi!");
```

Or, in case you want to use a Reply Keyboard:

```php
$menu[] = array("Button 1", "Button 2"); //two buttons on the same row
$menu[] = array("Button 3"); //button on a different row
sendMessage("Hi!", $menu, 1);
```

Or, in case you want to use an Inline Keyboard:

```php
$menu[] = array(array("text" => "Button 1", "callback_data" => "button_1"), array("text" => "Button 2", "callback_data" => "button_2")); //two buttons on the same row
$menu[] = array(array("text" => "Button 3", "callback_data" => "button_3")); //button on a different row
sendMessage("Hi!", $menu, 2);
```