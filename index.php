<?php

require_once 'vendor/autoload.php';

use RPGBank\Api;
use RPGBank\Log;
use RPGBank\Conf;

try {

	$telegram = new Api(Conf::BOT_API_TOKEN);

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

	// Waiting for incoming updates
	$update = $telegram->getWebhookUpdates();
	$message = $update->getMessage();

	if ($message && $message->getText()[0] == '/') {
		Log::debug('Incoming message: ' . $update);
		$response = $telegram->commandsHandler(true);
		Log::debug('Command processed:' . $response);
	}

} catch (Error $e) {
	Log::error('Error: ' . $e);
}
?>