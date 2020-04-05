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
			$this->replyWithMessage([
				'text' => \L::accountnotfound($username)
			]);
			return;
		} catch (InvalidUsernameException $e) {
			$this->replyWithMessage([
				'text' => \L::invalidusername($username)
			]);
			return;
		}
		
		if ($this->getArguments()['custom'] != $username) {
			$this->replyWithMessage([
				'text' => \L(closeaccount_confirm, [$username, $username])
			]);
			return;
		}

		AccountService::deleteAccount($message);

		$this->replyWithMessage([
			'text' => \L::closeaccount_success($username)
		]);
	}
}