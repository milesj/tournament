
DROP TABLE IF EXISTS `{prefix}teams_users`;

CREATE TABLE `{prefix}teams_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`team_id` int(11) NOT NULL,
	`user_id` int(11) NOT NULL,
	`created` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `team_id` (`team_id`),
	KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;