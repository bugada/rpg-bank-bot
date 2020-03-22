<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Services\AccountService;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class BalanceCommand extends Command {

	protected $name = 'balance';

	protected $description = \L::balance_description;

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
				'text' => sprintf(\L::accountnotfound, $username)
			]);
			return;
		} catch (InvalidUsernameException $e) {
			$this->replyWithMessage([
				'text' => sprintf(\L::invalidusername, $username)
			]);
			return;
		}

		$balance = AccountService::getBalance(
			$message->getChat()->getId(), 
			$message->getFrom()->getId(), 
			$message->getFrom()->getUsername()
		);

		$balance = number_format($balance , 0 , "," , ".");

		$this->replyWithMessage([
			'text' => sprintf(\L::balance_success, $username, $balance)
		]);
	}
}