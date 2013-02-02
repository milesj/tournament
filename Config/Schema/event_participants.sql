
DROP TABLE IF EXISTS `{prefix}events_teams`;

CREATE TABLE `{prefix}events_teams` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`event_id` int(11) NOT NULL,
	`team_id` int(11) NOT NULL,
	`player_id` int(11) NOT NULL,
	`isReady` smallint(6) NOT NULL DEFAULT '0',
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `event_id` (`event_id`),
	KEY `team_id` (`team_id`),
	KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;