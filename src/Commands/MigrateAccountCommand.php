<?php

namespace RPGBank\Commands;

use RPGBank\Services\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class MigrateAccountCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'migrateaccount';

	protected $description = 'migrate an exisiting account';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {

		$message = $this->getUpdate()->getMessage();

		try {
			AccountService::existingAccount($message);
		} catch (AccountNotFoundException $e) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'we cannot find your account. Please open a new account with: '. PHP_EOL .
					  '/openaccount';
			$this->replyWithMessage(compact('text'));
			return;
		} catch (InvalidUsernameException $e) {
			AccountService::migrateAccount($message);
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'your account has been succesfully migrated to your current username.';
			$this->replyWithMessage(compact('text'));
			return;
		} catch (\Exception $e) {
			$text = 'A generic error has occurred.';
			$this->replyWithMessage(compact('text'));
			return;
		}

		$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'your account is already migrated.';
		$this->replyWithMessage(compact('text'));
		return;
	}
}