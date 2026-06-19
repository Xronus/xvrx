CREATE DATABASE IF NOT EXISTS `wow_website`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS `auth`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS `characters`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `auth`;

CREATE TABLE IF NOT EXISTS `account` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `salt` binary(32) DEFAULT NULL,
  `verifier` binary(32) DEFAULT NULL,
  `joindate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL DEFAULT '',
  `reg_mail` varchar(255) NOT NULL DEFAULT '',
  `expansion` tinyint unsigned NOT NULL DEFAULT 2,
  `totaltime` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `account_banned` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int unsigned NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `account_premium` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int unsigned NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `realmlist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT 'Xronus',
  `address` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `port` int unsigned NOT NULL DEFAULT 8085,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `account` (`id`, `username`, `email`, `reg_mail`, `expansion`, `totaltime`) VALUES
(1, 'TEST', 'test@example.com', 'test@example.com', 2, 0),
(2, 'ADMIN', 'admin@example.com', 'admin@example.com', 2, 0);

INSERT IGNORE INTO `realmlist` (`id`, `name`, `address`, `port`) VALUES
(1, 'Xronus', '127.0.0.1', 8085);

USE `characters`;

CREATE TABLE IF NOT EXISTS `characters` (
  `guid` int unsigned NOT NULL AUTO_INCREMENT,
  `account` int unsigned NOT NULL DEFAULT 0,
  `name` varchar(12) NOT NULL,
  `race` tinyint unsigned NOT NULL DEFAULT 1,
  `class` tinyint unsigned NOT NULL DEFAULT 1,
  `level` tinyint unsigned NOT NULL DEFAULT 80,
  `totaltime` int unsigned NOT NULL DEFAULT 0,
  `leveltime` int unsigned NOT NULL DEFAULT 0,
  `totalKills` int unsigned NOT NULL DEFAULT 0,
  `online` tinyint unsigned NOT NULL DEFAULT 0,
  `logout_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`guid`),
  KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `arena_team` (
  `arenaTeamId` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL,
  `type` tinyint unsigned NOT NULL DEFAULT 2,
  `rating` int unsigned NOT NULL DEFAULT 0,
  `seasonWins` int unsigned NOT NULL DEFAULT 0,
  `seasonGames` int unsigned NOT NULL DEFAULT 0,
  `weekWins` int unsigned NOT NULL DEFAULT 0,
  `weekGames` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`arenaTeamId`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `arena_team_member` (
  `arenaTeamId` int unsigned NOT NULL,
  `guid` int unsigned NOT NULL,
  PRIMARY KEY (`arenaTeamId`, `guid`),
  KEY `guid` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `characters` (`guid`, `account`, `name`, `race`, `class`, `level`, `totaltime`, `leveltime`, `totalKills`, `online`, `logout_time`) VALUES
(1, 1, 'Arthas', 1, 2, 80, 528000, 22000, 1542, 1, UNIX_TIMESTAMP()),
(2, 1, 'Jaina', 1, 8, 80, 421000, 18000, 892, 1, UNIX_TIMESTAMP()),
(3, 2, 'Thrall', 2, 7, 80, 611000, 26000, 2104, 0, UNIX_TIMESTAMP()),
(4, 2, 'Sylvanas', 5, 3, 80, 389000, 16000, 1760, 1, UNIX_TIMESTAMP());

INSERT IGNORE INTO `arena_team` (`arenaTeamId`, `name`, `type`, `rating`, `seasonWins`, `seasonGames`, `weekWins`, `weekGames`) VALUES
(1, 'Frozen Dawn', 2, 2140, 52, 70, 8, 11),
(2, 'Northrend Echo', 3, 1985, 44, 66, 6, 10),
(3, 'Ashen Verdict', 5, 1830, 38, 58, 5, 9);

INSERT IGNORE INTO `arena_team_member` (`arenaTeamId`, `guid`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(3, 2),
(3, 3),
(3, 4);
