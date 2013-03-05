
DROP TABLE IF EXISTS `{prefix}leagues`;

CREATE TABLE `{prefix}leagues` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`game_id` int(11) NOT NULL,
	`region_id` int(11) NOT NULL,
	`name` varchar(50) NOT NULL,
	`slug` varchar(50) NOT NULL,
	`description` text NOT NULL,
	`logo` varchar(255) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `game_id` (`game_id`),
	KEY `region_id` (`region_id`),
	KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;