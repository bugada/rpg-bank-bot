<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Storage\AccountService;

class BalanceCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'balance';

	protected $description = 'show the user account balance';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$message = $this->getUpdate()->getMessage();

		if (!AccountService::existingAccount($message)) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'we cannot find your account. Please open a new account with: '. PHP_EOL .
					  '/openaccount';
			$this->replyWithMessage(compact('text'));
			return;
		}

		$balance = AccountService::getBalance(
			$message->getChat()->getId(), 
			$message->getFrom()->getId(), 
			$message->getFrom()->getUsername()
		);

		$balance = number_format($balance , 0 , "," , ".");

		$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'your current balance is: ' . $balance;
		$this->replyWithMessage(compact('text'));
	}
}