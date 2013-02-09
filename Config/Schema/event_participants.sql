
DROP TABLE IF EXISTS `{prefix}event_participants`;

CREATE TABLE `{prefix}event_participants` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`event_id` int(11) NOT NULL,
	`team_id` int(11) DEFAULT NULL,
	`player_id` int(11) DEFAULT NULL,
	`status` smallint(6) NOT NULL DEFAULT '0',
	`wins` int(11) NOT NULL DEFAULT '0',
	`losses` int(11) NOT NULL DEFAULT '0',
	`ties` int(11) NOT NULL DEFAULT '0',
	`points` int(11) NOT NULL DEFAULT '0',
	`isReady` tinyint(4) NOT NULL DEFAULT '0',
	`wonOn` datetime DEFAULT NULL,
	`defeatedOn` datetime DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `event_id` (`event_id`),
	KEY `team_id` (`team_id`),
	KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;