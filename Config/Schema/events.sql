
DROP TABLE IF EXISTS `{prefix}events`;

CREATE TABLE `{prefix}events` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`league_id` int(11) NOT NULL,
	`division_id` int(11) NOT NULL,
	`type` smallint(6) NOT NULL DEFAULT '0',
	`for` smallint(6) NOT NULL DEFAULT '0',
	`name` varchar(50) NOT NULL,
	`start` datetime DEFAULT NULL,
	`end` datetime DEFAULT NULL,
	`signupStart` datetime DEFAULT NULL,
	`signupEnd` datetime DEFAULT NULL,
	`requiredMembers` int(11) NOT NULL,
	`maxTeams` int(11) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `league_id` (`league_id`),
	KEY `division_id` (`division_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
