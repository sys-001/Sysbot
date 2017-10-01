# Sysbot

Sysbot is a simple Telegram Bot Framework. It's written in PHP and it's ready-to-use.

### Installation

Just put all repo files on a free host with PHP5 (or later) support and SSL enabled.

### Instantiation

You must set a webhook to *bot.php* file in your web hosting, passing `token` as parameter.
> Explaination:
>`https://api.telegram.org/botTOKEN/setWebhook?url=https://yourhost.com/sysbot/bot.php?token=TOKEN`

Note: You can connect multiple bots to the same *bot.php* file. (You can "clone" your bot, yay!)

### Changing settings

You can edit current bot settings by editing *DATA/management/settings.json* file. Available settings are:
- `"parse_mode"` -> Messages parse mode. Can be `"HTML"` or `"Markdown"`, according to [Telegram API Docs](https://core.telegram.org/bots/api#formatting-options);
- `"send_actions"` -> Bot will send actions like "typing", "sending file", etc. Can be `true` or `false`;
- `"in_maintenance"` -> When in maintenance mode, bot will reply a custom written message and will ignore all commands. Can be `true` or `false`;
- `"maintenance_msg"` -> Message sent by bot in maintenance mode;
- `"password"` -> Password used to update the bot.
- `"test_mode"` -> When in test mode, Sysbot will use Telegram Test Bot API (A.K.A. Deep Telegram Bot API); please note that you must create a bot with Deep Telegram's BotFather, and use its token, otherwise you will get a 401 Unauthorized Error. Can be `true` or `false`. P.S.: You can signup to Telegram Test even from [Telegram Web](https://web.telegram.org/?test=1).

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

If you want to add an user as an administrator, simply add his Telegram User ID in *DATA/management/admins* file in a new line.

>Example:
>123456789
>012345678
>Etc.

If you don't want to add it manually, you can add this code to *commands.php* file:
>```php
>if(strpos($update->message->text, "/admin ") === 0 and $isAdmin){
>$target = str_replace("/admin ", "", $update->message->text);
>file_put_contents("DATA/management/admins", PHP_EOL.$target);
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

It will also return Sysbot settings.

### Updating

A function to update *bot.php* is also included, so, if you want to keep your Sysbot version up to date, do this:
>`https://yourhost.com/sysbot/bot.php?upgrade=true&password=YOURSHA512HASHEDPASSWORD`

You can set your custom password, just edit "password" field in [settings file](#changing-settings).
>Remember, default password is: *password* .

Once you've start upgrading, Sysbot will check if its version is equals to current version. If not, it will download latest *bot.php* base, replacing current version.

### Documentation

(coming soon) Take a look [here](https://sys-001.github.io/Sysbot).

### Issues

If are experiencing an issue, contact me on [Telegram](https://telegram.me/sys001), or send me an [email](mailto:sys001@etlgr.com).

Anyway, read the documentation first. :D
