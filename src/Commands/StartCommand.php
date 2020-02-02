<?php

namespace RPGBank\Commands;

class StartCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'start';

	protected $description = 'start this bot';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$text = 'Welcome to RPG Bank' . PHP_EOL . PHP_EOL .
				  'This bot mimics a bank for role play games ' .
				  'and can only be used in public or private groups' . PHP_EOL . PHP_EOL .
				  'To see available commands type /help' . PHP_EOL . PHP_EOL .
				  'You can view the bot source code at' . PHP_EOL .
				  'https://github.com/bugada/rpg-bank-bot'; 
				  
		$this->replyWithMessage(compact('text'));
	}

}

?>