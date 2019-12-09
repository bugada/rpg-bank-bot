<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Storage\AccountService;

class ChangeBalanceCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'changebalance';

	protected $description = 'add or remove funds for the given user account (admin only)';

	public function isForAdmin() {
		return true;
	}

	public function handle($arguments) {
		Log::debug("ChangeBalanceCommand " . $arguments);

		// Split arguments into username and amount
		$args = explode(',', $arguments);

		// Check format
		if (count($args) != 2 || $args[0] == null || args[0] == "" ||
			$args[1] == null || !preg_match('/^-?\d+$/', $args[1])) {
			$text = 'Wrong format. Usage /changebalance name,amount';
			$this->replyWithMessage(compact('text'));
			return;
		}

		$username = $args[0];
		$amount = intval($args[1]);

		Log::debug("Username: " . $username);
		Log::debug("Amount: " . $amount);

		$message = $this->getUpdate()->getMessage();

		$accountData = AccountService::getAccountByUsername($message, $username);

		if ($accountData === FALSE) {
			$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
					  'we cannot find the ' . $username . ' account.';
			$this->replyWithMessage(compact('text'));
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

		$text = 'Hello ' . $message->getFrom()->getUsername() . ',' . PHP_EOL . 
				  'balance updated by ' . $amount . ' for ' . $username . '.' . PHP_EOL .
				  'New balance is: ' .$balance;
		$this->replyWithMessage(compact('text'));
	}
}