<?php

namespace RPGBank\Services;

class MessageService {

	public static function isFromGroup($message) {
		$chatType = $message->getChat()->getType();
		return ($chatType == 'group' || $chatType == 'supergroup');
	}

}