<?php

namespace RPGBank\Commands;

use RPGBank\Services\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class MigrateAccountCommand extends Command {

	protected $name = 'migrateaccount';

	protected $description = \L::migrateaccount_description;

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
			AccountService::migrateAccount($message);
			$this->replyWithMessage([
				'text' => \L::migrateaccount_success($username)
			]);
			return;
		}

		$this->replyWithMessage([
			'text' => \L::migrateaccount_alreadymigrated($username)
		]);
	}
}