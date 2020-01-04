<?php

namespace RPGBank;

use RPGBank\Conf;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

class Log {

	protected static $instance;

	public static function getLogger() {
		if (! self::$instance) {
			self::configureInstance();
		}

		return self::$instance;
	}

	protected static function configureInstance() {
		$dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . Conf::LOG_DIR_NAME;

		if (!file_exists($dir)){
			mkdir($dir, 0755, true);
		}

		$logger = new Logger('RPGBank');
		$logger->pushProcessor(new IntrospectionProcessor());
		$logger->pushProcessor(new WebProcessor());
		$logger->pushHandler(new RotatingFileHandler($dir . DIRECTORY_SEPARATOR . Conf::LOG_FILE_NAME, Conf::LOG_MAX_FILES, Conf::LOG_LEVEL));

		self::$instance = $logger;
	}

	public static function debug($message, array $context = []){
		self::getLogger()->debug($message, $context);
	}

	public static function info($message, array $context = []){
		self::getLogger()->info($message, $context);
	}

	public static function notice($message, array $context = []){
		self::getLogger()->notice($message, $context);
	}

	public static function warning($message, array $context = []){
		self::getLogger()->warning($message, $context);
	}

	public static function error($message, array $context = []){
		self::getLogger()->error($message, $context);
	}

	public static function critical($message, array $context = []){
		self::getLogger()->critical($message, $context);
	}

	public static function alert($message, array $context = []){
		self::getLogger()->alert($message, $context);
	}

	public static function emergency($message, array $context = []){
		self::getLogger()->emergency($message, $context);
	}

}