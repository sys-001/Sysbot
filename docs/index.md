# Sysbot Documentation

Here, you can find some useful informations about Sysbot.

### Index

- [Installation](#installation)
- [Instantiation](#instantiation)
- [Settings](#settings)
- [Available methods](#available-methods)

### Installation

Requirements:
- php5; (or later)
- php5-curl; (or later)
- a web server (apache, nginx, etc.).

Install required dependencies, then clone Sysbot repo:
```bash
$ git clone -b old https://github.com/sys-001/Sysbot.git
```

All done, you have successfully installed Sysbot!

### Instantiation

##### Using Webhooks

If you have a domain with SSL support enabled, you can set a webhook to *bot.php* file:
>`https://api.telegram.org/botTOKEN/setWebhook?url=https://domain.com/path-to-sysbot/bot.php?token=TOKEN`

Where TOKEN is your real bot token (remember: '123456789:qwertyuiop', and not 'bot123456789:qwertyuiop').

*Note: if you are quite noob, you may want to use [Webhook-AutoSet](https://sys-001.github.io/Webhook-AutoSet/).*

##### Using getUpdates

Just delete the webhook and start bot.

### Settings

You can edit current bot settings by editing *DATA/management/settings.json* file.

##### General Settings

Available settings:
- `"parse_mode"` -> Messages parse mode. Can be "HTML" or "Markdown", according to [Telegram API Docs](https://core.telegram.org/bots/api#formatting-options);
- `"send_actions"` -> Bot will send actions like "typing", "sending file", etc. Can be true or false;
- `"in_maintenance"` -> When in maintenance mode, bot will reply a custom written message and will ignore all commands. Can be true or false;
- `"maintenance_msg"` -> Message sent by bot in maintenance mode;
- `"test_mode"` -> When in test mode, Sysbot will use Telegram Test Bot API (A.K.A. Deep Telegram Bot API); please note that you must create a bot with Deep Telegram's BotFather, and use its token, otherwise you will get a 401 Unauthorized Error. Can be true or false. P.S.: You can signup to Telegram Test even from [Telegram Web](https://web.telegram.org/?test=1).

### Available methods

You can find a list of available methods [here](methods/index.md).
