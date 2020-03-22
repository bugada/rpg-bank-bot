<?php

namespace RPGBank\Commands;

use RPGBank\Log;
use RPGBank\Services\UserService;
use RPGBank\Services\MessageService;
use RPGBank\Exceptions\InvalidCommandException;

abstract class Command extends \Telegram\Bot\Commands\Command {


	public function handle() {

		$message = $this->getUpdate()->getMessage();

		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug('Message:' . $message);
		}

		if ($message !== null && $message->has('text')) {

			if (!MessageService::isFromGroup($message)) {
				$this->replyWithMessage([
					'text' => 'this bot can only be used in groups or supergroups'
				]);
				throw new InvalidCommandException();
			}

			if (Log::isEnabled(Log::DEBUG)) {
				Log::debug('IsForAdmin: ' . ($this->isForAdmin() ? 'true' : 'false'));
			}
			if ($this->isForAdmin()) {
				if (!UserService::isAdmin($this->telegram, $message)) {
					$this->replyWithMessage([
						'text' => 'this command can be invoked by admin only'
					]);
					throw new InvalidCommandException();
				}
			}
			
		}
	}
}

?>