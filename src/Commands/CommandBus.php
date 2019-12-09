<?php

namespace RPGBank\Commands;

use RPGBank\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

class CommandBus extends \Telegram\Bot\Commands\CommandBus {

	public function getCommand($message) {
		$match = $this->parseCommand($message);
		if (!empty($match)) {
			$command = $match[1];
		}

		return $this->commands[$command];
	}
}