# telegram-slack

A small code that allows you to integrate messaging between your bot, in slack, and back. Works only in groups in telegram.

---
### What do you need?
1. Server with nginx\apache and php support.
2. Domain and SSL.
3. Hands.

---
### Install

Let's imagine that you have already set up a domain with SSL and uploaded files.

First, you need to make a webhook for telegram:

`https://api.telegram.org/bot[TOKEN]/setWebhook?url=[URL]/bot.php`

Make a SLACK bot (app) with the following settings:

1. **OAuth & Permissions**
REDIRECT URL:
`[URL]/out.php`

2. **Scopes** (for all):
* channels:history
* channels:manage
* channels:read
* channels:write.invites
* channels:write.topic
* chat:write
* chat:write.public
 

3. **Event Subscriptions**
Enter URL: `[URL]/out.php`
Set the settings for messages below.

4. In **App unfurl domains** enter the domain/subdomain.


Then specify the variables in the config.php files: 
```php
define('TG_TOKEN', '******************'); //TOKEN TELEGRAM BOT
define('SLACK_TOKEN', '******************'); //TOKEN BOT (APP) SLACK
define('SLACK_CHANNNEL', '***************'); //ID CHANNEL IN SLACK
define('WEB_SITE', '********************'); //URL YOUR DOMAIN
```

At the end where we write the domain, be sure to include a link without a slash at the end.

---

## IMPORTANT!

1. In the telegram bot settings, you must disable private mode for it, allowing it to see all messages.

2. The telegram bot will only work with groups. If you write to it in private messages, the bot will not respond and will not forward messages to the chat.

3. All files that the bot sends as a link to slack will be stored for 30 days on your server.
