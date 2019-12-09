<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Storage\AccountService;

class OpenAccountCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'openaccount';

	protected $description = 'open a new account';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$message = $this->getUpdate()->getMessage();

		if (AccountService::existingAccount($message)) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'you already have an account.';
			$this->replyWithMessage(compact('text'));
			return;
		}

		AccountService::createAccount($message);

		$text = 'Welcome on board ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'your account has been opened successfully.';
		$this->replyWithMessage(compact('text'));
	}
}