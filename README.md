# Sysbot

<p align="center">
  <img src="logo.png" title="Sysbot-Logo" width="50%">
</p>

**NOTE: README and documentation are incomplete: I'll update them as soon as possible.**

Sysbot is a simple Telegram Bot Framework. It's written in PHP and it's ready-to-use.

### Installation

Just put all repo files on a free host with PHP5 (or later) support and SSL enabled.

### Instantiation

Open *setup.php* in your browser and fill all fields.

### Changing settings

You can edit current bot settings by editing *DATA/management/settings.json* file. Available settings are:
- `"admins"` -> List of Bot administrators. Read [bot administration section](#bot-administration) for further instructions;
- `"parse_mode"` -> Messages parse mode. Can be `"HTML"` or `"Markdown"`, according to [Telegram API Docs](https://core.telegram.org/bots/api#formatting-options);
- `"send_actions"` -> Bot will send actions like "typing", "sending file", etc. Can be `true` or `false`;
- `"in_maintenance"` -> When in maintenance mode, bot will reply a custom written message and will ignore all commands. Can be `true` or `false`;
- `"maintenance_msg"` -> Message sent by bot in maintenance mode;
- `"password"` -> SHA512 hashed password used to upgrade the framework;
- `"test_mode"` -> When in test mode, Sysbot will use Telegram Test Bot API (A.K.A. Deep Telegram Bot API); please note that you must create a bot with Deep Telegram's BotFather, and use its token, otherwise you will get a 401 Unauthorized Error. Can be `true` or `false`. P.S.: You can signup to Telegram Test even from [Telegram Web](https://web.telegram.org/?test=1).

Other settings are for getUpdates mode:
- `"enabled"` -> Bot will use getUpdates with specified token. Can be `true` or `false`;
- `"token"` -> Encrypted token used by bot in getUpdates mode.



### Creating commands and responses

If you want to create custom commands with custom responses, you must edit *commands.php*. I've included some sample code there, so you can easily understand how it works.

### Available types and methods

Read [documentation](https://sys-001.github.io/Sysbot) to see all available types, and some other methods. (You can send files, forward messages, etc., so why don't you take a look?)

### Usage stats

You can get users and groups where bot is used using `getUsers` and `getGroups` methods; I've written a sample command - available to [bot administrators](#bot-administration) only - which shows you all users and groups number.

### Bot Administration

Do you want to create a command which can be used only by a few people? Well, you can do this: just check if `$isAdmin` is true when you catch an update.

>Example:
>```php
>if($update->message->text == "/whoami" and $isAdmin) {
>sendMessage("root");
>}
>```

If you want to add an user as an administrator, simply add his Telegram User ID in *DATA/management/settings.json* file, under *admins* field.

>Example:
>```json
>"admins": [
>"123456789",
>"234567890"
>],
>```

If you don't want to add it manually, you can add this code to *commands.php* file:
>```php
>if(strpos($update->message->text, "/admin ") === 0 and $isAdmin){
>$target = str_replace("/admin ", "", $update->message->text);
>$admins = $settings->admins; //don't edit directly settings
>$admins[] = $target;
>$settings->admins = $admins; //pushing new settings
>file_put_contents("DATA/management/settings.json", $settings); //saving updated settings
>sendMessage("UserID $target added to admins list.");
>}
>```

### Addons

The *ADDONS* folder contains two plugins:
- *post.php*, which allows you to send broadcast messages to each user and group;
- *antiflood.php*, which temporarily blocks an user from using the bot.
 
### Web access

I've also written a small function to see some informations directly from a browser:
>`https://yourhost.com/sysbot/bot.php?info=true`

You can also upgrade the framework through it.

You can set your custom password, just edit "password" field in [settings file](#changing-settings).
>Remember, default password is: *password* .



### Documentation

(coming soon) Take a look [here](https://sys-001.github.io/Sysbot).

### Issues

If are experiencing an issue, contact me on [Telegram](https://telegram.me/sys002), or send me an [email](mailto:sys-001@etlgr.com).

Anyway, read the documentation first. :D
