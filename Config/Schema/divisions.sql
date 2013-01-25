
DROP TABLE IF EXISTS `{prefix}divisions`;

CREATE TABLE `{prefix}divisions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`league_id` int(11) NOT NULL,
	`name` varchar(35) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `league_id` (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;