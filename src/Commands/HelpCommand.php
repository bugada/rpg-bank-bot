<?php

namespace RPGBank\Commands;

class HelpCommand extends \Telegram\Bot\Commands\HelpCommand {

	protected $description = 'get a list of commands';

	public function isForAdmin() {
		return false;
	}

}

?>