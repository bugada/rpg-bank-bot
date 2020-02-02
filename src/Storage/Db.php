<?php

namespace RPGBank\Storage;

use RPGBank\Log;
use RPGBank\Conf;

class Db {
	 private $_connection;
	 private static $_instance; //The single instance
	 private $_host = Conf::DB_HOST;
	 private $_username = Conf::DB_USERNAME;
	 private $_password = Conf::DB_PASSWORD;
	 private $_database = Conf::DB_NAME;

	 /*
	 Get an instance of the Database
	 @return Instance
	 */
	 public static function getInstance()
	 {
		  if (!self::$_instance) { // If no instance then make one
				self::$_instance = new self();
		  }
		  return self::$_instance;
	 }

	// Constructor
	private function __construct() {
		try {
			$this->_connection = new \PDO("mysql:host=$this->_host;dbname=$this->_database", $this->_username, $this->_password);
			$this->_connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			Log::debug('Connected to database');
		} catch (PDOException $e) {
			Log::error('Error while connecting to database' . $e->getMessage());
		}
	}

	 // Magic method clone is empty to prevent duplication of connection
	private function __clone() {
	}

	 // Get mysql pdo connection
	public function getConnection() {
		return $this->_connection;
	}
}
