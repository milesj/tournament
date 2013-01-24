
DROP TABLE IF EXISTS `{prefix}seasons`;

CREATE TABLE `{prefix}seasons` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`league_id` int(11) NOT NULL,
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
	KEY `league_id` (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
