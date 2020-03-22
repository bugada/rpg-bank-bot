<?php

namespace RPGBank\Services;

use Telegram\Bot\Objects\User;

class UserService {

	public static function isAdmin($telegramApi, $message) {
		$status = $telegramApi->getChatMember([
			'chat_id' => $message->getChat()->getId(), 
			'user_id' => $message->getFrom()->getId()
		])->get('status');
		return ($status == 'creator' || $status == 'administrator');
	}

}