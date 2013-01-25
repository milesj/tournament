
DROP TABLE IF EXISTS `{prefix}regions`;

CREATE TABLE `{prefix}regions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`slug` varchar(15) NOT NULL,
	`description` varchar(255) NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `{prefix}regions` (`id`, `name`, `slug`, `description`, `created`, `modified`) VALUES
	(1, 'United States', 'US', '', NOW(), NOW()),
	(2, 'Europe', 'EU', '', NOW(), NOW()),
	(3, 'Korea', 'KR', '', NOW(), NOW());