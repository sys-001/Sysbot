# answerInlineQuery

[Return to available methods list](index.md)

### Description:

Prints specified result in an Inline Query. See possible results [here](https://core.telegram.org/bots/api#inlinequeryresult).

### Parameters:

| Name | Required | Type |
|------|----------|------|
|result|Yes|array|
|switch\_to\_pm_text|No|string|
|switch\_to\_pm_payload|No|string|

### Usage:

```php
answerInlineQuery($result, $switch_to_pm_text, $switch_to_pm_payload);
```

##### Example:

```php
$result[] = array('type' => 'article', 'id' => '001' , 'title' => 'Sample title', 'description' => 'Sample description', 'message_text' => 'Sample article'); //inlineQueryResultArticle
answerInlineQuery($result);
```

Or, if you want to show a button to switch to private chat:

```php
$result[] = array('type' => 'article', 'id' => '001' , 'title' => 'Sample title', 'description' => 'Sample description', 'message_text' => 'Sample article'); //inlineQueryResultArticle
answerInlineQuery($result, "Switch to private chat", "payload");
```