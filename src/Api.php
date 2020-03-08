<?php

namespace RPGBank;

use RPGBank\Log;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Update;
use RPGBank\Commands\CommandBus;
use RPGBank\Services\UserService;
use RPGBank\Services\MessageService;
use RPGBank\Exceptions\InvalidCommandException;

class Api extends \Telegram\Bot\Api {

	public function getChatMember(array $params) {
		$response = $this->post('getChatMember', $params);

		return new User($response->getDecodedBody());
	}

	public function getChatAdministrators(array $params) {
		$response = $this->post('getChatAdministrators', $params);

		return new User($response->getDecodedBody());
	}

	public function getCommandBus() {
		if (is_null($this->commandBus)) {
			return $this->commandBus = new CommandBus($this);
		}

		return $this->commandBus;
	 }

	protected function processCommand(Update $update) {
		$message = $update->getMessage();

		if ($message !== null && $message->has('text')) {

			// check if it is valid command
			$command = $this->getCommandBus()->getCommand($message->getText());
			if (is_null($command)) {
				throw new InvalidCommandException($message->getText());
			}

			// process only /start and /help if not from (super)group
			if ($message->getText() != "/start" && $message->getText() != "/help") {
				if (!MessageService::isFromGroup($message)) {
					$response = $this->sendMessage([
						'chat_id' => $message->getChat()->getId(), 
						'text' => 'this bot can only be used in groups or supergroups'
					]);
					return;
				}
			}

			Log::debug('IsForAdmin: ' . ($command->isForAdmin() ? 'true' : 'false'));
			if ($command->isForAdmin()) {
				if (!UserService::isAdmin($this, $message)) {
					$this->sendMessage([
						'chat_id' => $message->getChat()->getId(), 
						'text' => 'this command can be invoked by admin only'
					]);
					return;
				}
			}

			$this->getCommandBus()->handler($message->getText(), $update);
		}
	}
}