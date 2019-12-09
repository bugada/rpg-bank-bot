<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Storage\AccountService;

class CloseAccountCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'closeaccount';

	protected $description = 'close the account';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$message = $this->getUpdate()->getMessage();

		if (!AccountService::existingAccount($message)) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'we cannot find your account. ';
			$this->replyWithMessage(compact('text'));
			return;
		}

		if ($arguments != $message->getFrom()->getUsername()) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL .
					  'closing your account will delete all your data. No refund is possible.' . PHP_EOL . 
					  'Please confirm the deletion of your account with this command: ' . PHP_EOL .
					  '/closeaccount '. $message->getFrom()->getUsername();
			$this->replyWithMessage(compact('text'));
			return;
		}

		AccountService::deleteAccount($message);

		$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'your account has been deleted successfully.';
		$this->replyWithMessage(compact('text'));
	}
}