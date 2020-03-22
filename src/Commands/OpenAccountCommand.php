<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Services\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class OpenAccountCommand extends Command {

	protected $name = 'openaccount';

	protected $description = \L::openaccount_description;

	public function isForAdmin() {
		return false;
	}

	public function handle() {
		parent::handle();

		$message = $this->getUpdate()->getMessage();
		$username = $message->getFrom()->getUsername();

		try {
			AccountService::existingAccount($message);
		} catch (AccountNotFoundException $e) {
			AccountService::createAccount($message);
			$this->replyWithMessage([
				'text' => sprintf(\L::openaccount_success, $username)
			]);
			return;
		} catch (InvalidUsernameException $e) {
			$this->replyWithMessage([
				'text' => sprintf(\L::invalidusername, $username)
			]);
			return;
		}

		$this->replyWithMessage([
			'text' => sprintf(\L::openaccount_alreadyexisiting, $username)
		]);
	}
}