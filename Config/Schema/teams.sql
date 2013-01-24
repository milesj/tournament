
DROP TABLE IF EXISTS `{prefix}teams`;

CREATE TABLE `{prefix}teams` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`status` smallint(6) NOT NULL DEFAULT '0',
	`name` varchar(50) NOT NULL,
	`slug` varchar(50) NOT NULL,
	`description` varchar(255) NOT NULL,
	`logo` varchar(255) NOT NULL,
	`members` int(11) NOT NULL DEFAULT '0',
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
