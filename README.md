# RPGBank
A Telegram Bot developed using PHP/MySql that mimic basic bank features for role play games and more.

It can be used in Telegram groups only.

Any group member can open an account and then see the balance.
The admin can change the balance of group users.

This bot store basic data to do his job: groupid, userid and username

You can see a demo at [@RPGBank](https://t.me/RPGBankBot) in Telegram.

## Commands available

* `/help` - get a list of commands
* `/changebalance` - add or remove funds for the given user account (admin only)
* `/balance` - show the user account balance
* `/openaccount` - open a new account
* `/closeaccount` - close the account
* `/migrateaccount` - migrate an exisiting account (not implemented yet)

## How to install

Make sure you have [Composer](https://getcomposer.org/download/) installe on yuor system. Clone this repository and in the root dir type this command:

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
