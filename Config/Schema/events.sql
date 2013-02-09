
DROP TABLE IF EXISTS `{prefix}events`;

CREATE TABLE `{prefix}events` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`game_id` int(11) NOT NULL,
	`league_id` int(11) NOT NULL,
	`division_id` int(11) NOT NULL,
	`type` smallint(6) NOT NULL DEFAULT '0',
	`for` smallint(6) NOT NULL DEFAULT '0',
	`seed` smallint(6) NOT NULL DEFAULT '0',
	`round` smallint(6) DEFAULT NULL,
	`name` varchar(50) NOT NULL,
	`slug` varchar(50) NOT NULL,
	`start` datetime DEFAULT NULL,
	`end` datetime DEFAULT NULL,
	`signupStart` datetime DEFAULT NULL,
	`signupEnd` datetime DEFAULT NULL,
	`isRunning` tinyint(4) NOT NULL DEFAULT '0',
	`isFinished` tinyint(4) NOT NULL DEFAULT '0',
	`isGenerated` tinyint(4) NOT NULL DEFAULT '0',
	`requiredMembers` int(11) NOT NULL,
	`maxParticipants` int(11) NOT NULL,
	`poolSize` int(11) DEFAULT NULL,
	`event_participant_count` int(11) NOT NULL DEFAULT '0',
	`match_count` int(11) NOT NULL DEFAULT '0',
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `game_id` (`game_id`),
	KEY `league_id` (`league_id`),
	KEY `division_id` (`division_id`),
	KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
