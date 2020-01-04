CREATE TABLE `accounts` (
  `groupId` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`groupId`,`userId`) USING BTREE,
  ADD UNIQUE KEY `USERNAME` (`groupId`,`userId`,`username`);
COMMIT;
