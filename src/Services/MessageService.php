<?php

namespace RPGBank\Services;

use RPGBank\Log;

class MessageService {

	public static function isFromGroup($message) {
		$chatType = $message->getChat()->getType();
		Log::debug('Chat type: ' . $chatType);
		return ($chatType == 'group' || $chatType == 'supergroup');
	}

}