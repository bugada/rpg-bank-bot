<?php

require_once 'vendor/autoload.php';

use RPGBank\Api;
use RPGBank\Log;
use RPGBank\Conf;

try {

	$telegram = new Api(Conf::BOT_API_TOKEN);

	// Adding available commands
	$telegram->addCommands([
		RPGBank\Commands\HelpCommand::class,
		RPGBank\Commands\ChangeBalanceCommand::class,
		RPGBank\Commands\BalanceCommand::class,
		RPGBank\Commands\OpenAccountCommand::class,
		RPGBank\Commands\CloseAccountCommand::class,
		RPGBank\Commands\MigrateAccountCommand::class
	]);

	// Waiting for incoming updates
	$update = $telegram->getWebhookUpdates();
	Log::debug('Incoming message: ' . $update);

	$message = $update->getMessage();

	// Check that is a group or supergroup
	$chatType = $message->getChat()->getType();
	Log::debug('Chat type: ' . $chatType);
	if ($chatType != 'group' && $chatType != 'supergroup') {
		// Throw handled error, this bot can only be used in groups or supergroups
		$response = $telegram->sendMessage([
			'chat_id' => $message->getChat()->getId(), 
			'text' => 'this bot can only be used in groups or supergroups'
		]);
		Log::debug('Response: ' . $response);
		return;
	}

	Log::debug('Processing command...');
	$update = $telegram->commandsHandler(true);
	Log::debug('Command processed:' . $update);

} catch (Error $e) {
	Log::error('Error: ' . $e);
}
?>