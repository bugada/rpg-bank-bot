<?php

namespace RPGBank;

use Monolog\Logger;

class Conf {
	const BOT_API_TOKEN = 'your telegram api token';

	const DB_HOST   = 'localhost';
	const DB_NAME = 'your db name';
	const DB_USERNAME = 'root';
	const DB_PASSWORD = '';

	const LOG_LEVEL = Logger::WARNING;
	const LOG_DIR_NAME = 'logs';
	const LOG_FILE_NAME = 'rpgbank.log';
	const LOG_MAX_FILES = 5;
}

?>