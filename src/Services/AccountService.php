<?php

namespace RPGBank\Services;

use RPGBank\Log;
use RPGBank\Storage\Db;
use RPGBank\Exceptions\AccountNotFoundException;
use RPGBank\Exceptions\InvalidUsernameException;

class AccountService {

	public static function createAccount($message) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			insert into accounts 
			(groupId, userId, username) 
			values (?, ?, ?)'
		);

		$ok = $sth->execute([
				$message->getChat()->getId(), 
				$message->getFrom()->getId(), 
				$message->getFrom()->getUsername()
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			// TODO throw exception
			return;
		}

		$db = null;
	}

	public static function deleteAccount($message) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			delete
			from accounts 
			where groupId = ? 
			and userId = ? 
			and username = ?'
		);

		$ok = $sth->execute([
				$message->getChat()->getId(), 
				$message->getFrom()->getId(), 
				$message->getFrom()->getUsername()
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			// TODO throw exception
			return;
		}

		$db = null;

	}

	public static function migrateAccount($message) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			update accounts 
			set username = ?
			where groupId = ?
			and userId = ?'
		);

		$ok = $sth->execute([
			$message->getFrom()->getUsername(),
			$message->getChat()->getId(),
			$message->getFrom()->getId()
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			// TODO throw exception
			return;
		}

		$db = null;
	}

	public static function existingAccount($message) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			select username
			from accounts 
			where groupId = ? 
			and userId = ?'
		); 

		$ok = $sth->execute([
			$message->getChat()->getId(), 
			$message->getFrom()->getId()
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			throw new InvalidSqlException();
		}

		$username = $sth->fetchColumn();
		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug('existingAccount::Username: ' . $username);
		}

		$msgUsername = $message->getFrom()->getUsername();

		if (!$username) {
			throw new AccountNotFoundException($msgUsername);
		} else if ($username != $msgUsername) {
			throw new InvalidUsernameException($msgUsername);
		}

		$db = null;

	}

	public static function getAccountByUsername($message, $username) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			select groupId, userId, username
			from accounts 
			where groupId = ? 
			and username = ?'
		); 

		$ok = $sth->execute([
			$message->getChat()->getId(), 
			$username
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			throw new InvalidSqlException();
		}

		$accountData = $sth->fetch(\PDO::FETCH_ASSOC);
		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug('getAccountByUsername::AccountData: ' . $accountData);
		}

		$db = null;

		return $accountData;

	}

	public static function getBalance($groupId, $userId, $username) {
		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			select balance 
			from accounts 
			where groupId = ? 
			and userId = ?
			and username = ?' 
		); 

		$ok = $sth->execute([
			$groupId, 
			$userId, 
			$username
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			throw new InvalidSqlException();
		}

		$balance = $sth->fetchColumn();
		if (Log::isEnabled(Log::DEBUG)) {
			Log::debug('getBalance::Balance: ' . $balance);
		}

		$db = null;
		return $balance;
	}

	public static function updateBalance($message, $userId, $amount) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			update accounts 
			set balance = balance + (?)
			where groupId = ? and userId = ?'
		);

		$ok = $sth->execute([
				$amount,
				$message->getChat()->getId(), 
				$userId
		]);

		if (!$ok) {
			Log::error('Error on executing query');
			throw new InvalidSqlException();
		}

		$db = null;
	}
	
}

?>