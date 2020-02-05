# RPGBank
A Telegram Bot developed using PHP/MySql that mimics basic bank features for role play games and more.

It can only be used in Telegram groups.

Any group member can open an account and then see his balance.
The group admins can change the balance of group members.

This bot stores basic data to do his job: groupid, userid and username

You can see a demo at [@RPGBank](https://t.me/RPGBankBot) in Telegram, add it to a public or private group.

## Commands available

* `/help` - get a list of commands
* `/changebalance` - add or remove funds for the given user account (admin only)
* `/balance` - show the user account balance
* `/openaccount` - open a new account
* `/closeaccount` - close the account
* `/migrateaccount` - migrate an exisiting account for users that changed their username.

## How to install

Make sure you have [Composer](https://getcomposer.org/download/) installed on your system. Clone this repository and in the root dir type this command:

`composer install`

This will install all the required dependencies in the `vendor` dir.

## How to deploy

You can easily deploy this bot on your preferred host copying all the files including the ones installed by Composer.

Please not that only the `index.php` must be visible  to the internet, the other files should be protected against reading.

For security reasons, you can rename the `index.php` file to something weird to make it harder to invoke by malicious users.

Grab your api-key from [@BothFather](https://t.me/BotFather) and register your webhook (the index.php, eventually renamed).

Configure the bot editing the file `src/Conf.php` with your api-key, and your db settings.

Import in the MySql database the `rpgbank.sql`, this will create one table for storing users data.

Since this bot uses PDO, you can easily switch to another database engine just editing the connection string (see `src/Storage/Db.php`)

## Support this project

If you want help you can create pull requests to enhance or fix this bot.

You can help me installing and using [Brave Browser](https://brave.com/bug776) using this link https://brave.com/bug776, or if you have some spare BAT you can make a donation through the Brave Reward program.
