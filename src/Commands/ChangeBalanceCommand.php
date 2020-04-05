<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Services\AccountService;

class ChangeBalanceCommand extends Command {

	protected $name = 'changebalance';

	protected $description = \L::changebalance_description;

	protected $pattern = '.+';

	public function isForAdmin() {
		return true;
	}

	public function handle() {
		parent::handle();

		$args = explode(',', $this->getArguments()['custom']);
		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug("ChangeBalanceCommand " . json_encode($args));
		}

		// Check format
		if (count($args) != 2 || $args[0] == null || $args[0] == "" ||
			$args[1] == null || !preg_match('/^-?\d+$/', $args[1])) {
			$this->replyWithMessage([
				'text' => \L::changebalance_wrongformat
			]);
			return;
		}

		$username = $args[0];
		$amount = intval($args[1]);

		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug("Username: " . $username);
			Log::debug("Amount: " . $amount);
		}
		
		$message = $this->getUpdate()->getMessage();

		$accountData = AccountService::getAccountByUsername($message, $username);

		if ($accountData === FALSE) {
			$this->replyWithMessage([
				'text' => \L(changebalance_accountnotfound, [$message->getFrom()->getUsername(), $username])
			]);
			return;
		}

		AccountService::updateBalance($message, $accountData['userId'], $amount);

		$balance = AccountService::getBalance(
			$message->getChat()->getId(), 
			$accountData['userId'],
			$accountData['username']
		);

		$balance = number_format($balance , 0 , "," , ".");
		$amount = number_format($amount , 0 , "," , ".");

		$text = \L(changebalance_success, [$message->getFrom()->getUsername(), $amount, $username, $balance]);
		$this->replyWithMessage(compact('text'));
	}
}