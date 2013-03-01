
DROP TABLE IF EXISTS `{prefix}matches`;

CREATE TABLE `{prefix}match_scores` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`match_id` int(11) NOT NULL,
	`home_id` int(11) DEFAULT NULL,
	`away_id` int(11) DEFAULT NULL,
	`status` smallint(6) NOT NULL DEFAULT '0',
	`game` smallint(6) NOT NULL,
	`screenshot` varchar(255) NOT NULL,
	`replay` varchar(255) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `match_id` (`match_id`),
	KEY `home_id` (`home_id`),
	KEY `away_id` (`away_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;