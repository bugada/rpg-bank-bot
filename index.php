<?php

require_once 'vendor/autoload.php';

use \Telegram\Bot\Api;
use RPGBank\Conf;
use RPGBank\Log;
use RPGBank\Exceptions\CommandException;

try {

	// Get Telegram Sdk Api instance
	$telegram = new Api(Conf::BOT_API_TOKEN);

	// Waiting for incoming updates
	$update = $telegram->getWebhookUpdates();

	// Setup internalization
	$languageCode = $update->getMessage()->getFrom()->getLanguageCode();
	if (Log::isEnabled(Log::DEBUG)) {
		Log::debug("Language: " . $languageCode);
	}

	$i18n = new \i18n(__DIR__ . Conf::LANGUAGE_PATH, __DIR__ . Conf::LANGUAGE_CACHE_PATH, Conf::DEFAULT_LANGUAGE);
	$i18n->setForcedLang($languageCode);
	$i18n->init();

	// Adding available commands
	$telegram->addCommands([
		RPGBank\Commands\StartCommand::class,
		RPGBank\Commands\HelpCommand::class,
		RPGBank\Commands\ChangeBalanceCommand::class,
		RPGBank\Commands\BalanceCommand::class,
		RPGBank\Commands\OpenAccountCommand::class,
		RPGBank\Commands\CloseAccountCommand::class,
		RPGBank\Commands\MigrateAccountCommand::class
	]);

	// Process command
	$telegram->commandsHandler(true);

} catch (Exception | Error $e) {
	Log::error($e);
}

?>