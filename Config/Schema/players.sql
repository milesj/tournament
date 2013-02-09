
DROP TABLE IF EXISTS `{prefix}players`;

CREATE TABLE `{prefix}players` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`wins` int(11) NOT NULL DEFAULT '0',
	`losses` int(11) NOT NULL DEFAULT '0',
	`ties` int(11) NOT NULL DEFAULT '0',
	`points` int(11) NOT NULL DEFAULT '0',
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;