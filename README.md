# Sysbot (minimalistic version)

<p align="center">
  <img src="logo.png" title="Sysbot-Logo" width="50%">
</p>

An ultra-light version of Sysbot.

## Installation

Install PHP 7.3 (or newer), the JSON extension and [Composer](https://getcomposer.org/), then retrieve the dependencies:

`$ composer install`

## Usage

The framework supports all available methods, just take a look at the [Telegram bot API documentation](https://core.telegram.org/bots/api).

Make sure to pass all arguments by using a key-value array, like this:
```php
$bot->sendMessage(['chat_id' => 123456789, 'text' => 'Hi!']);
```