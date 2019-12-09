<?php

namespace RPGBank\Storage;

use RPGBank\Log;

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

	public static function existingAccount($message) {

		$db = Db::getInstance()->getConnection();

		$sth = $db->prepare('
			select count(*) 
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

		$count = $sth->fetchColumn();
		Log::debug("existingAccount::Count: " . $count);

		$db = null;

		return $count > 0;
		
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
			// TODO throw exception
			return;
		}

		$accountData = $sth->fetch(\PDO::FETCH_ASSOC);
		Log::debug("getAccountByUsername::AccountData: " . $accountData);

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
			// TODO throw exception
			return;
		}

		$balance = $sth->fetchColumn();
		Log::debug("getBalance::Balance: " . $balance);

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
			// TODO throw exception
			return;
		}

		$db = null;
	}
	
}

?>