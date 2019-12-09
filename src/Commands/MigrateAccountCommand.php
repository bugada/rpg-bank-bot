<?php

namespace RPGBank\Commands;

class MigrateAccountCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'migrateaccount';

	protected $description = 'migrate an exisiting account';

	public function isForAdmin() {
		return false;
	}

	public function handle($arguments) {
		$text = 'not yet implemented';
		$this->replyWithMessage(compact('text'));
	}
}