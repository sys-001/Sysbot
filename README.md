# Sysbot

<p align="center">
  <img src="logo.png" title="Sysbot-Logo" width="50%">
</p>

**NOTE: This version is deprecated and will not be supported anymore. I suggest you to switch to master branch's version.**

Sysbot is a simple Telegram Bot Framework. It's written in PHP and it's ready-to-use.

### Installation

Just put all repo files on a free host with PHP5 (or later) support and SSL enabled (needed only if you plan to use a webhook).

### Instantiation

Open *setup.php* in your browser and fill all fields.

### Changing settings

You can edit current bot settings by editing *DATA/management/settings.json* file. Available settings are:
- `"admins"` -> List of Bot administrators. Read [bot administration section](#bot-administration) for further instructions;
- `"parse_mode"` -> Messages parse mode. Can be `"HTML"` or `"Markdown"`, according to [Telegram API Docs](https://core.telegram.org/bots/api#formatting-options);
- `"send_actions"` -> Bot will send actions like "typing", "sending file", etc. Can be `true` or `false`;
- `"in_maintenance"` -> When in maintenance mode, bot will reply a custom written message and will ignore all commands. Can be `true` or `false`;
- `"maintenance_msg"` -> Message sent by bot in maintenance mode;
- `"test_mode"` -> When in test mode, Sysbot will use Telegram Test Bot API (A.K.A. Deep Telegram Bot API); please note that you must create a bot with Deep Telegram's BotFather, and use its token, otherwise you will get a 401 Unauthorized Error. Can be `true` or `false`. P.S.: You can signup to Telegram Test even from [Telegram Web](https://web.telegram.org/?test=1).

Note: If you are lazy/noob, you can delete *DATA/management/settings.json* file: opening *bot.php* through your browser, you will be able to re-run setup.

### Creating commands and responses

If you want to create custom commands with custom responses, you must edit *commands.php*. I've included some sample code there, so you can easily understand how it works. However, I'm writing a little example here:

>```php
>if($update->message->text == "/command") {
>sendMessage("Response");
>}
>```

For simple commands, you can also use one-line version:

>```php
>if($update->message->text == "/command") sendMessage("Response");
>```

### Available types and methods

Read [documentation](#documentation) to see all available types, and some other methods. (You can send files, forward messages, etc., so why don't you take a look?)

### Bot Administration

Do you want to create a command which can be used only by a few people? Well, you can do this: just check if `$is_admin` is true when you catch an update.

>Example:
>```php
>if($update->message->text == "/whoami" and $is_admin) {
>sendMessage("root");
>}
>```

If you want to add an user as an administrator, simply add his Telegram User ID in *DATA/management/settings.json* file, under *admins* field.

>Example:
>```json
>{
>  "admins": [
>    "123456789",
>    "234567890"
>  ]
>}
>```

If you don't want to add it manually, you can add this code to *commands.php* file:
>```php
>if(strpos($update->message->text, "/admin ") === 0 and $is_admin){
>$target = str_replace("/admin ", "", $update->message->text);
>$admins = $settings->admins; //don't edit directly settings
>$admins[] = $target;
>$settings->admins = $admins; //pushing new settings
>file_put_contents("DATA/management/settings.json", $settings); //saving updated settings
>sendMessage("UserID $target added to admins list.");
>}
>```
 
### Documentation

Take a look [here](https://sysbot.readthedocs.io/en/latest/).

### Issues

I won't take care of this version anymore, don't pm me. :D
