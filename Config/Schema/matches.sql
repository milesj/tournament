
DROP TABLE IF EXISTS `{prefix}matches`;

CREATE TABLE `{prefix}matches` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`league_id` int(11) NOT NULL,
	`event_id` int(11) NOT NULL,
	`a_id` int(11) NOT NULL,
	`b_id` int(11) NOT NULL,
	`type` smallint(6) NOT NULL DEFAULT '0',
	`bracket` smallint(6) NOT NULL DEFAULT '0',
	`aOutcome` int(11) NOT NULL DEFAULT '0',
	`bOutcome` int(11) NOT NULL DEFAULT '0',
	`aScore` int(11) NOT NULL,
	`bScore` int(11) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `league_id` (`league_id`),
	KEY `event_id` (`event_id`),
	KEY `a_id` (`a_id`),
	KEY `b_id` (`b_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;