<?php

namespace RPGBank;

use RPGBank\Log;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Update;
use RPGBank\Commands\CommandBus;

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

			// Get member status
			//TODO: use PHPCache and getChatAdministrators(chat_id) for better performances
			$status = $this->getChatMember([
				'chat_id' => $message->getChat()->getId(), 
				'user_id' => $message->getFrom()->getId()
			])->get('status');

			Log::debug('ChatMemberStatus: ' . $status);

			$isForAdmin = $this->getCommandBus()->getCommand($message->getText())->isForAdmin();
			Log::debug('IsForAdmin: ' . ($isForAdmin ? 'true' : 'false'));

			if ($isForAdmin && $status != 'creator' && $status != 'administrator') {
				$this->sendMessage([
					'chat_id' => $message->getChat()->getId(), 
					'text' => 'this command can be invoked by admin only'
				]);
				return;
			}

			$this->getCommandBus()->handler($message->getText(), $update);
		}
	}

}