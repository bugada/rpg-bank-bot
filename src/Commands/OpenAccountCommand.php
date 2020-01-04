<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Storage\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class OpenAccountCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'openaccount';

	protected $description = 'open a new account';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$message = $this->getUpdate()->getMessage();

		try {
			AccountService::existingAccount($message);
		} catch (AccountNotFoundException $e) {
			AccountService::createAccount($message);
			$text = 'Welcome on board ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'your account has been opened successfully.';
			$this->replyWithMessage(compact('text'));
			return;
		} catch (InvalidUsernameException $e) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'you already have an account but is not associated with your current username.'. PHP_EOL .
					  'Please use the /migrateaccount to fix this.';
			$this->replyWithMessage(compact('text'));
			return;
		} catch (\Exception $e) {
			$text = 'A generic error has occurred.';
			$this->replyWithMessage(compact('text'));
			return;
		}

		$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'you already have an active account.';
		$this->replyWithMessage(compact('text'));
		return;

	}
}