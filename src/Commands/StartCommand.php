<?php

namespace RPGBank\Commands;

class StartCommand extends \Telegram\Bot\Commands\Command {

	protected $name = 'start';

	protected $description = \L::start_description;

	public function isForAdmin() {
		return false;
	}

	public function handle() {

		$this->replyWithMessage([
			'text' => \L::start_intro
		]);
	}

}

?>