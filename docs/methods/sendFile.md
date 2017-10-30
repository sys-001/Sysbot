# sendFile

[Return to available methods list](index.md)

### Description:

Sends a file of given URL, with or without a caption, to target Chat ID. Note: you must specify file type; available types are:
| Name | Type |
|------|------|
|photo|Photo|
|video|Video|
|videoNote|Video note|
|audio|Audio|
|voice|Voice note|
|document|Other files|

### Parameters:

| Name | Required | Type |
|------|----------|------|
|url|Yes|string|
|caption|Yes|string|
|type|Yes|string|
|chat_id|No|int|

### Usage:

```php
sendFile($url, $caption, $type, $chat_id);
```

##### Example:

```php
sendFile("host.com/file.jpg", "", "photo");
```

Or, if you want to use a caption:

```php
sendFile("host.com/file.jpg", "Sample description", "photo");
```