<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Services\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class CloseAccountCommand extends Command {

	protected $name = 'closeaccount';

	protected $description = \L::closeaccount_description;

	protected $pattern = '.+';

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
			throw new CommandException(
				sprintf(\L::accountnotfound, $username)
			);
		} catch (InvalidUsernameException $e) {
			throw new CommandException(
				sprintf(\L::invalidusername, $username)
			);
		}
		
		if ($this->getArguments()['custom'] != $username) {
			$this->replyWithMessage([
				'text' => sprintf(\L::closeaccount_confirm, $username, $username)
			]);
		}

		AccountService::deleteAccount($message);

		$this->replyWithMessage([
			'text' => sprintf(\L::closeaccount_success, $username)
		]);
	}
}