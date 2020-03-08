<?php

namespace RPGBank\Services;

use RPGBank\Log;

class UserService {

	public static function isAdmin($telegramApi, $message) {
		$status = $telegramApi->getChatMember([
			'chat_id' => $message->getChat()->getId(), 
			'user_id' => $message->getFrom()->getId()
		])->get('status');

		Log::debug('ChatMemberStatus: ' . $status);
		return ($status == 'creator' || $status == 'administrator');
	}

}