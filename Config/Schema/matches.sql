
DROP TABLE IF EXISTS `{prefix}matches`;

CREATE TABLE `{prefix}matches` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`league_id` int(11) NOT NULL,
	`event_id` int(11) NOT NULL,
	`home_id` int(11) DEFAULT NULL,
	`away_id` int(11) DEFAULT NULL,
	`type` smallint(6) NOT NULL DEFAULT '0',
	`bracket` smallint(6) NOT NULL DEFAULT '0',
	`winner` smallint(6) NOT NULL DEFAULT '0',
	`round` smallint(6) DEFAULT NULL,
	`pool` smallint(6) DEFAULT NULL,
	`order` smallint(6) DEFAULT NULL,
	`homeOutcome` int(11) NOT NULL DEFAULT '0',
	`homePoints` int(11) NOT NULL,
	`awayOutcome` int(11) NOT NULL DEFAULT '0',
	`awayPoints` int(11) NOT NULL,
	`playOn` datetime DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `league_id` (`league_id`),
	KEY `event_id` (`event_id`),
	KEY `home_id` (`home_id`),
	KEY `away_id` (`away_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;