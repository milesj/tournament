
DROP TABLE IF EXISTS `{prefix}games`;

CREATE TABLE `{prefix}games` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`image` varchar(255) NOT NULL,
	`imageSmall` varchar(255) NOT NULL,
	`imageIcon` varchar(255) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;